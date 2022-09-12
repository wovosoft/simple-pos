<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use Wovosoft\LaravelCommon\Helpers\Messages;
use Throwable;

class SupplierController extends Controller
{
    private array $rules = [
        "name" => ["required", "string"],
        "phone" => ["nullable", "string"],
        "email" => ["nullable", "string"],
        "company" => ["nullable", "string"],
        "address" => ["nullable", "string"],
        "description" => ["nullable", "string"],
    ];

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules);
            $supplier = new Supplier;
            $supplier->forceFill($data);
            $supplier->saveOrFail();
            DB::commit();
            return Messages::success();
        }
        catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }
    public function edit(Request $request, Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules);

            $supplier->forceFill($data);
            $supplier->saveOrFail();
            DB::commit();
            return Messages::success();
        }
        catch (Throwable $e) {
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function show(Request $request, Supplier $supplier)
    {
        return $supplier;
    }

    public function destroy(Request $request, Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $supplier->deleteOrFail();
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
        return Supplier::query()->paginate();
    }
}
