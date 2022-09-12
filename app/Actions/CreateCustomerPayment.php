<?php

namespace App\Actions;

use App\Models\Customer;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;

class CreateCustomerPayment
{
    private Customer $customer;

    public function __construct(Customer|int|null $customer) {
        if(is_int($customer)){
            $this->customer=Customer::query()->findOrFail($customer);
        }elseif($customer instanceof Customer){
            $this->customer= $customer;
        }
    }

    public static function of(Customer|int $customer)
    {
        return new static($customer);
    }

    public function take(float|int $amount)
    {
        DB::beginTransaction();
        try {
            //keep record
            $payment = new CustomerPayment();
            $payment->previous_balance = $this->customer->payable;
            $payment->amount = $amount;
            $payment->current_balance = $this->customer->payable + $amount;
            $payment->saveOrFail();

            //deduce payable
            $this->customer->deductPayable($amount);

            DB::commit();
            return $payment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
