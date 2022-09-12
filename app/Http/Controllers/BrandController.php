<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Throwable;
use Wovosoft\LaravelCommon\Helpers\Messages;


class BrandController extends Controller
{
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $data = $request->validate([
                "name"=>["required","string"]
            ]);
            $brand = new Brand;
            $brand->forceFill($data);
            $brand->saveOrFail();
            DB::commit();
            return Messages::success();
        }catch(Throwable $e){
            DB::rollBack();
            return Messages::failed($e);
        }
    }
    public function edit(Request $request, Brand $brand){
        DB::beginTransaction();
        try{
            $data = $request->validate([
                "name"=>["required","string"]
            ]);
           
            $brand->forceFill($data);
            $brand->saveOrFail();
            DB::commit();
            return Messages::success();
        }catch(Throwable $e){
            DB::rollBack();
            return Messages::failed($e);
        }
    }
  
    public function show(Request $request, Brand $brand){
        return $brand;
    }

    public function destroy(Request $request, Brand $brand){
        DB::beginTransaction();
        try{
            $brand->deleteOrFail();
            DB::commit();
            return Messages::success();
        }catch(Throwable $e){
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function index(Request $request){
        return Brand::query()->paginate();
    }
}
