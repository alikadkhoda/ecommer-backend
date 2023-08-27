<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request){

        if(auth('sanctum')->check()){

            $user_id=auth('sanctum')->user()->id;
            $product_id=$request->product_id;
            $product_qty=$request->product_qty;
            $productCheck=Product::where('id',$product_id)->first();
            if($productCheck){
                if(Cart::where('product_id',$product_id)->where('user_id',$user_id)->exists()){

                    return response()->json([
                        'status'=>409,
                        'message'=>' قبلا اضافه شده است '.$productCheck->name
                    ]);
                }
                else{
                    $cartItem=new Cart;
                    $cartItem->user_id=$user_id;
                    $cartItem->product_id=$product_id;
                    $cartItem->product_qty=$product_qty;
                    $cartItem->save();
                    return response()->json([
                        'status'=>201,
                        'message'=>'اضافه شد'
                    ]);
                }
               
            }
            else{

                return response()->json([
                    'status'=>404,
                    'message'=>'محصول یافت نشد'
                ]);
            }

           
        }
        else{
            return response()->json([
                'status'=>401,
                'message'=>'لطفا وارد شوید'
            ]);
        }
    }
    public function viewCart(){
        if(auth('sanctum')->check()){
            $user_id=auth('sanctum')->user()->id;
            $cartItems=Cart::where('user_id',$user_id)->get();
            return response()->json([
                'status'=>200,
                'cart'=>$cartItems
            ]);
        }
        else{
            return response()->json([
                'status'=>401,
                'message'=>' لطفا وارد شوید برای مشاهده کارت'
            ]);
        }
    }

    public function updateQuantity($cart_id,$scope){
        if(auth('sanctum')->check()){
            $user_id=auth('sanctum')->user()->id;
            $cartItem=Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($scope=='inc'){
                $cartItem->product_qty+=1;
            }
            else if($scope=='dec'){
                $cartItem->product_qty-=1;
            }
            $cartItem->update();
            return response()->json([
                'status'=>200,
                'message'=>'تعداد محصول آپدیت شد'
            ]);
        }
        else{
            return response()->json([
                'status'=>401,
                'message'=>' لطفا وارد شوید   '
            ]);
        }
    }

    public function deleteCart($cart_id){
        if(auth('sanctum')->check()){
            $user_id=auth('sanctum')->user()->id;
            $cartItem=Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($cartItem){
                $cartItem->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>'محصول مورد نظر از سبد خرید حذف شد'
                ]);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'محصول مورد نظر پیدا نشد'
                ]);
            }
        }
        else{
            return response()->json([
                'status'=>401,
                'message'=>' لطفا وارد شوید   '
            ]);
        }
    }
}
