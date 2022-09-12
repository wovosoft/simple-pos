<?php

namespace App\Actions;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;

class CreateSupplierPayment
{
    private Supplier $supplier;
    private string $purpose;
    private string $description;

    public function __construct(Supplier|int|null $supplier)
    {
        if (is_int($supplier)) {
            $this->supplier = Supplier::query()->findOrFail($supplier);
        } elseif ($supplier instanceof Supplier) {
            $this->supplier = $supplier;
        }
    }

    /**
     * Instance of Supplier Payment
     * Made it static so that it is build at first call
     *
     * @param Supplier $supplier
     * @return static
     */
    public static function to(Supplier|int $supplier): static
    {
        return new static($supplier);
    }

    public function setPurpose(string $purpose): static
    {
        $this->purpose = $purpose;
        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function pay(float|int $amount): SupplierPayment
    {
        DB::beginTransaction();
        try {
            //keep record
            $payment = new SupplierPayment();
            $payment->previous_balance = $this->supplier->payable;
            $payment->amount = $amount;
            $payment->current_balance = $this->supplier->payable + $amount;
            $payment->purpose = $this->purpose;
            $payment->description = $this->description;
            $payment->saveOrFail();

            //deduce payable
            $this->supplier->deductPayable($amount);
            DB::commit();
            return $payment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
