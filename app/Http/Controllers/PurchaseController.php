<?php

namespace App\Http\Controllers;

use App\Actions\CreatePurchase;
use App\Actions\CreateSupplierPayment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Throwable;
use Wovosoft\LaravelCommon\Helpers\Messages;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                "supplier_id" => ["numeric", "exists:suppliers,id"],
                "tax" => ["numeric", "nullable"],
                "discount" => ["numeric", "nullable"],
                "instant_payment" => ["numeric", "nullable"],

                "items" => ["array", "required"],
                "items.*.quantity" => ["required", "numeric", "min:0"],
                "items.*.cost" => ["required", "numeric"],
            ]);

            $$purchase = CreatePurchase::from($request->input("supplier_id"))
                ->setTax($request->input("tax") ?: 0)
                ->setDiscount($request->input("discount") ?: 0)
                ->setItems($request->input("items"))
                ->save();

            if ($purchase && $request->input("instant_payment"))  {
                CreateSupplierPayment::to($request->input("supplier_id"))
                    ->pay($request->input("instant_payment"));
                //store instant payment id with purchase
                //todo: 
            }

            return Messages::success();
        } catch (Throwable $e) {
            return Messages::failed($e);
        }
    }


    public function index(Request $request)
    {
        return Purchase::query()->paginate();
    }
}
