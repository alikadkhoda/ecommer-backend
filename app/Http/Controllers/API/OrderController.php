<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderitems;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders=Order::all();
        return response()->json([
            'status'=>200,
            'orders'=>$orders
        ]);
    }
    public function detail($order_id){
        $orderItems=Orderitems::where('order_id',$order_id)->get();
        if($orderItems){
             return response()->json([
            'status'=>200,
            'orderItems'=>$orderItems
             ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'سفارشی با آیدی مورد نظر پیدا نشد'
                 ]);
        }
       
    }
}
