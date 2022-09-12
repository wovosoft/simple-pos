<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Wovosoft\LaravelCommon\Helpers\Messages;
use Throwable;

class CategoryController extends Controller
{
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $data = $request->validate([
                "name"=>["required","string"]
            ]);
            $category = new Category();
            $category->forceFill($data);
            $category->saveOrFail();
            DB::commit();
            return Messages::success();
        }catch(Throwable $e){
            DB::rollBack();
            return Messages::failed($e);
        }
    }
    public function edit(Request $request, Category $category){
        DB::beginTransaction();
        try{
            $data = $request->validate([
                "name"=>["required","string"]
            ]);
           
            $category->forceFill($data);
            $category->saveOrFail();
            DB::commit();
            return Messages::success();
        }catch(Throwable $e){
            DB::rollBack();
            return Messages::failed($e);
        }
    }
  
    public function show(Request $request, Category $category){
        return $category;
    }

    public function destroy(Request $request, Category $category){
        DB::beginTransaction();
        try{
            $category->deleteOrFail();
            DB::commit();
            return Messages::success();
        }catch(Throwable $e){
            DB::rollBack();
            return Messages::failed($e);
        }
    }

    public function index(Request $request){
        return Category::query()->paginate();
    }
}
