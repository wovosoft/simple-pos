<?php

namespace App\Actions;

use App\Models\Customer;
use App\Models\ProductItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateSale
{
    private int $customerId;
    private float|int $total;
    private float|int $tax = 0;
    private float|int $discount = 0;
    private float|int $payable;

    private array $items;

    public function __construct(Customer|int|null $customer)
    {
        if (is_int($customer)) {
            $this->customerId = $customer;
        } elseif ($customer instanceof Customer) {
            $this->customerId = $customer->id;
        }
    }

    public static function of(Customer|int $customer)
    {
        return new static($customer);
    }

    public function setCustomer(int $customerId): static
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function setDiscount(int|float $amount): static
    {
        $this->discount = $amount;
        return $this;
    }

    public function setTax(int|float $amount): static
    {
        $this->tax = $amount;
        return $this;
    }

    public function setItems(array $items): static
    {
        $this->items = $items;
        $total = 0;
        foreach ($items as $item) {
            $total += $item["quantity"] * $item["price"];
        }
        $this->total = $total;
        $this->payable = $total - $this->discount + $this->tax;

        return $this;
    }

    /**
     * @param Sale $sale
     * @param array<SaleItem> $saleItems
     * @return void
     */
    private function processSilentActions(Sale $sale, array $saleItems)
    {
        $sale->customer->addPayable($sale->payable);
        foreach ($saleItems as $saleItem) {
            $saleItem->addToSale();
        }
    }

    public function save()
    {
        DB::beginTransaction();
        try {
            $sale = new Sale;
            $sale->customer_id = $this->customerId;
            $sale->total = $this->total;
            $sale->tax = $this->tax;
            $sale->discount = $this->discount;
            $sale->payable = $this->payable;
            $sale->saveOrFail();

            /** @var SaleItem[] $items **/
            $saleItems = [];
            foreach ($this->items as $item) {
                $productItem = ProductItem::query()->findOrFail($item["product_item_id"]);

                $saleItem = new SaleItem;
                $saleItem->product_id = $productItem->product_id;
                $saleItem->product_item_id = $productItem->id;
                $saleItem->sale_id = $item["sale_id"];
                $saleItem->quantity = $item["quantity"];
                $saleItem->delivered_quantity = $item["delivered_quantity"];

                //will be used to find profit = (price-cost) * quantity
                $saleItem->cost = $productItem->cost;
                $saleItem->price = $item["price"];
                $saleItem->saveOrFail();
                $saleItems[] = $saleItem;
            }
            $sale->items()->saveMany($saleItems);

            $this->processSilentActions($sale, $saleItems);

            DB::commit();
            return $sale;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
