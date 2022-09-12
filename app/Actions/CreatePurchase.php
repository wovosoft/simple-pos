<?php

namespace App\Actions;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreatePurchase
{
    private int $supplierId;
    private float|int $total = 0;
    private float|int $tax = 0;
    private float|int $discount = 0;
    private float|int $payable = 0;

    private array $items;

    public function __construct(Supplier|int|null $supplier)
    {
        if (is_int($supplier)) {
            $this->supplierId = $supplier;
        } elseif ($supplier instanceof Supplier) {
            $this->supplierId = $supplier->id;
        }
    }

    public static function from(Supplier|int $supplier)
    {
        return new static($supplier);
    }

    public function setSupplier(int $supplierId): static
    {
        $this->supplierId = $supplierId;
        return $this;
    }

    public function setTax(int|float $amount): static
    {
        $this->tax = $amount;
        return $this;
    }

    public function setDiscount(int|float $amount): static
    {
        $this->discount = $amount;
        return $this;
    }

    /**
     * Set Purchase Items
     * Tax and discount should be called before setting items
     *
     * @param array<PurchaseItem> $items
     * @return static
     */
    public function setItems(array $items): static
    {
        //set items
        $this->items = $items;

        //calculate total and payable
        $total = 0;
        foreach ($items as $item) {
            $total += ($item["quantity"] ?: 0) * ($item["cost"] ?: 0);
        }
        $this->total = $total;
        $this->payable = $total - $this->discount + $this->tax;


        return $this;
    }

    /**
     * Silent Actions. Calls inside DB transaction. No need to wrap again.
     *
     * @param Purchase $purchase
     * @param array<PurchaseItem> $purchaseItems
     * @return void
     */
    private function processSilentActions(Purchase $purchase, array $purchaseItems)
    {
        //todo: later thare is a plan of adding real payable, upcoming payable and total payable fields
        //explanation: 10 items are ordered, but 4 items are received. 
        //real payable = 4*cost
        //upcoming payable = 6*cost
        //total payable = 10*cost

        //add payable to supplier
        $purchase->supplier->addPayable($purchase->payable);

        foreach ($purchaseItems as $purchaseItem) {
            $purchaseItem->addToStock();
        }
    }

    public function save() : Purchase
    {
        DB::beginTransaction();
        try {
            // store plain data in database, and the process
            // further silent actions to set payable, stock etc.

            $purchase = new Purchase();
            $purchase->supplier_id = $this->supplierId;
            $purchase->total = $this->total;
            $purchase->tax = $this->tax;
            $purchase->discount = $this->discount;
            $purchase->payable = $this->payable;
            $purchase->saveOrFail();

            /** @var PurchaseItem[] $items **/
            $purchaseItems = [];

            foreach ($this->items as $item) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->product_id = $item["product_id"];
                $purchaseItem->purchase_id = $item["purchase_id"];
                $purchaseItem->quantity = $item["quantity"] ?: 0;
                $purchaseItem->received_quantity = $item["received_quantity"] ?: 0;
                $purchaseItem->cost = $item["cost"] ?: 0;
                $purchaseItem->saveOrFail();
                //product item id will be set in silent actions


                $purchaseItems[] = $purchaseItem;
            }

            //perform silent actions
            $this->processSilentActions($purchase, $purchaseItems);

            DB::commit();
            return $purchase;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
