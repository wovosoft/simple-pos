<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Wovosoft\LaravelCommon\Helpers\Messages;
use Throwable;

class ProductController extends Controller
{
    private array $rules=[
        "category_id" => ["nullable", "numeric", "exists:categories,id"],
        "brand_id" => ["nullable", "numeric", "exists:brands,id"],
        "name" => ["required", "string"],
        "code" => ["nullable", "string"],
        "description" => ["nullable", "string"],
        "cost" => ["numeric"],
        "price" => ["numeric"]
    ];
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules);

            $product = new Product;
            $product->forceFill($data);
            $product->saveOrFail();
            DB::commit();
            return Messages::success();
        }
        catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }
    public function edit(Request $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules);

            $product->forceFill($data);
            $product->saveOrFail();
            DB::commit();
            return Messages::success();
        }
        catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function show(Request $request, Product $product)
    {
        return $product;
    }

    public function destroy(Request $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->deleteOrFail();
            DB::commit();
            return Messages::success();
        }
        catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function index(Request $request)
    {
        return Product::query()->paginate();
    }
}
