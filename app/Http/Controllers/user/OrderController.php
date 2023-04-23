<?php

namespace App\Http\Controllers\user;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //All privileges for admins
    public function showOrderAdmin(): \Illuminate\Http\JsonResponse
    {
        $p = DB::table('orders')
            ->join('products', 'product_id', '=', 'products.id')
            ->select('products.product_name','products.price','orders.*')
            ->where('products.admin_id', '=', auth('admin-api')->user()->id)
            ->get();
        return response()->json(['orders'=>$p],200);
    }

    public function confirm(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try{
            $p = Order::find($id);

            if(!$p){
                return response()->json(['message' => 'not found'],404);
            }
                $p->status = $request->status;
                $p->save();
                return response()->json(['message' => 'Order successfully paid!'], 200);

        } catch(\Exception $e){
            return response()->json(['message' => $e],500);
        }
    }


    //All privileges for users
    public  function showConfirmedOrder($status): \Illuminate\Http\JsonResponse
    {
        $o = DB::table('orders')->where('status',$status)->get();
        return response()->json(['OrderConfirmed'=> $o],200);
    }

    public function showOrderUser(): \Illuminate\Http\JsonResponse
    {
        $p = Order::where('user_id',  auth('user-api')->user()->id)->get();
        return response()->json(['products' => $p],200);
    }

    public function order(Request $request): \Illuminate\Http\JsonResponse
    {
        try{

            $id = Product::where('code', $request->code)->select('id')->first();

            Order::create([
                'order_number' => uniqid(),
                'item_count' => $request->item_count,
                'quantity' => $request->quantity,
                'user_id' => auth('user-api')->user()->id,
                'product_id' => $id->id,
            ]);

            return response()->json(['message' => 'successfully send order!'], 200);

        } catch(\Exception $e){
            return response()->json(['Exception' => $e], 500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $p = Order::find($id);
        if (!$p) {
            return response()->json(['message' => 'not found'], 404);
        } elseif ($p->user_id == auth('user-api')->user()->id) {
            $p->delete();
            return response()->json(['message' => 'Order successfully deleted!'], 200);
        } else {
            return response()->json(['message' => "U can't delete order, Delete only urs order"], 403);
        }
    }
}
