<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\user\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

//"Incorrect integer value: '[{\"id\":1}]' for column `qazaqsoft`.`products`.`category_id` at row 1"
//"Incorrect integer value: '{\"id\":1}' for column `qazaqsoft`.`products`.`category_id` at row 1"


class ProductController extends Controller
{

    public function show(): \Illuminate\Http\JsonResponse
    {
        $p = Product::all();
        return response()->json(['products'=>$p],200);
    }

    public function showForAdmins(): \Illuminate\Http\JsonResponse
    {
       $p = Product::where('admin_id',  auth('admin-api')->user()->id)->get();
       return response()->json(['products' => $p],200);
    }

    public function showByCategory($category): \Illuminate\Http\JsonResponse
    {
        $p = DB::table('products')->where('category',$category)->get();
        return response()->json(['product'=>$p],200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            $photo = Str::random(32).".".$request->photo->getClientOriginalExtension();

            $id = Category::where('category_name', $request->category)->select('id')->first();

            Product::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'photo' => $photo,
                'category' => $request->category,
                'category_id' => $id->id,
                'admin_id' => auth('admin-api')->user()->id,
                'code'=>uniqid(),
            ]);

            Storage::disk('public')->put($photo, file_get_contents($request->photo));
            return response()->json(['message' => 'Product successfully stored!'], 200);

        } catch(\Exception $e){
            return response()->json(['Exception' => $e], 500);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try{
            $p = Product::find($id);
            $id = Category::where('category_name', $request->category)->select('id')->first();

            if(!$p){
                return response()->json(['message' => 'not found'],404);
            }
            elseif($p->admin_id == auth('admin-api')->user()->id){

                $p->product_name = $request->product_name;
                $p->description = $request->description;
                $p->price = $request->price;
                $p->category = $request->category;
                $p->category_id = $id->id;
                $p->code = uniqid();

                if($request->photo){
                    $storage = Storage::disk('public'); //public storage

                    //old image delete
                    if($storage->exists($p->photo))
                        $storage->delete($p->photo);

                    //Image name
                    $photo = Str::random(32).".".$request->photo->getClientOriginalExtension();
                    $p->photo = $photo;

                    //Image save in public folder
                    $storage->put($photo, file_get_contents($request->photo));
                }

                $p->save();
                return response()->json(['message' => 'Product successfully updated!'], 200);
            }
            else{
                return response()->json(['message' => "U can't update! ,It's not ur's product. U can edit only urs product!"],403);
            }

        } catch(\Exception $e){
            //return response()->json(['message' => 'word.s_w_wrong'],500);
            return response()->json(['message' => $e],500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $p = Product::find($id);
        if(!$p){
            return response()->json(['message' => 'not found'], 404);
        }
        elseif($p->admin_id == auth('admin-api')->user()->id){

            $storage = Storage::disk('public');
            if($storage->exists($p->photo))
                $storage->delete($p->photo);

            $p->delete();

            return response()->json(['message' => 'Product successfully deleted!'], 200);
        }
        else{
            return response()->json(['message' => "U can't delete product, Delete only urs product"], 403);
        }
    }

}
