<?php

namespace App\Http\Controllers;

use App\Actions\CreateCustomerPayment;
use App\Actions\CreateSale;
use App\Models\Sale;
use Illuminate\Http\Request;
use Throwable;
use Wovosoft\LaravelCommon\Helpers\Messages;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                "customer_id" => ["numeric", "exists:customers,id"],
                "tax" => ["numeric", "nullable"],
                "discount" => ["numeric", "nullable"],
                "instant_payment" => ["numeric", "nullable"],

                "items" => ["array"],
                "items.*.product_item_id" => ["required", "exists:product_items,id"],
                "items.*.quantity" => ["required", "numeric", "min:0"],
                "items.*.price" => ["required", "numeric"],
            ]);

            $sale = CreateSale::of($request->input("customer_id"))
                ->setTax($request->input("tax") ?: 0)
                ->setDiscount($request->input("discount") ?: 0)
                ->setItems($request->input("items"))
                ->save();

            if ($sale && $request->input("instant_payment"))  {
                CreateCustomerPayment::of($request->input("customer_id"))
                    ->take($request->input("instant_payment"));
                //store instant payment id with sale
                //todo: 
            }

            return Messages::success();
        } catch (Throwable $e) {
            return Messages::failed($e);
        }
    }


    public function index(Request $request)
    {
        return Sale::query()->paginate();
    }
}
