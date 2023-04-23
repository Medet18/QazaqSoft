<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\user\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function show(): \Illuminate\Http\JsonResponse
    {
        $c = Category::all();
        return response()->json(['categories' => $c],200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            Category::create([
                'category_name' => $request->category_name,
                'slug' => $request->slug,
            ]);

            return response()->json(['message' => 'Category successfully stored!'], 200);

        } catch(\Exception $e){
            return response()->json(['Exception' => $e], 500);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try{
            $p = Category::find($id);

            if(!$p){
                return response()->json(['message' => 'not found'],404);
            }
            $p->category_name = $request->category_name;
            $p->slug = $request->slug;
            $p->save();
            return response()->json(['message' => 'category successfully updated!'], 200);

        }catch(\Exception $e){
            return response()->json(['message' => $e],500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $p = Category::find($id);
        if(!$p){
            return response()->json(['message' => 'not found'], 404);
        }
        $p->delete();

        return response()->json(['message' => 'Category successfully deleted!'], 200);

    }
}
