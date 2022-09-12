<?php

namespace App\Http\Controllers;

use App\Actions\TakeCustomerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Throwable;
use Wovosoft\LaravelCommon\Helpers\Messages;


class CustomerController extends Controller
{
    private array $rules = [
        "name" => ["required", "string"],
        "phone" => ["nullable", "string"],
        "address" => ["nullable", "string"],
        "description" => ["nullable", "string"],
    ];

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules);
            $customer = new Customer;
            $customer->forceFill($data);
            $customer->saveOrFail();
            DB::commit();
            return Messages::success();
        } catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }
    public function edit(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules);

            $customer->forceFill($data);
            $customer->saveOrFail();
            DB::commit();
            return Messages::success();
        } catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function show(Request $request, Customer $customer)
    {
        return $customer;
    }

    public function destroy(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $customer->deleteOrFail();
            DB::commit();
            return Messages::success();
        } catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function index(Request $request)
    {
        return Customer::query()->paginate();
    }

    public function takePayment(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                "amount" => ["required", "numeric"]
            ]);
            $payment = new TakeCustomerPayment();
            
            $payment->of($customer)->take($request->input("amount"));


            DB::commit();
            return Messages::success();
        } catch (Throwable $th) {
            DB::rollBack();
            return Messages::failed($th);
        }
    }
}
