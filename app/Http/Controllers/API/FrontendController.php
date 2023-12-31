<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function category(){
        $category=Category::where('status','0')->get();
        return response()->json([
            'status'=>200,
            'category'=>$category
        ]);
    }
    public function carousel(){
        $carousel=Product::offset(0)->limit(3)->get();
        return response()->json([
            'status'=>200,
            'carousel'=>$carousel
        ]);
    }
    public function newsProduct(){
        $start=Product::count()-3;
        $newsProduct=Product::offset($start)->limit(3)->get();
        return response()->json([
            'status'=>200,
            'newsProduct'=>$newsProduct
        ]);
    }
    public function product($slug){
        $category=Category::where('slug',$slug)->where('status','0')->first();
        if($category){
            $product=Product::where('category_id',$category->id)->where('status','0')->get();
            if($product){
                return response()->json([
                    'status'=>200,
                    'product_data'=>[
                        'product'=>$product,
                        'category'=>$category
                    ]
                ]);
            }
            else{
                return response()->json([
                    'status'=>400,
                    'message'=>'محصول مورد نظر در دسترس نیست '
                ]);
            }
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'دسته‌بندی مورد نظر یافت نشد'
            ]);
        }
    }

    public function viewProduct($category_slug,$product_slug){
        $category=Category::where('slug',$category_slug)->where('status','0')->first();
        if($category){
            $product=Product::where('category_id',$category->id)->where('slug',$product_slug)->where('status','0')->first();
            if($product){
                return response()->json([
                    'status'=>200,
                    'product'=>$product,
                    'category'=>$category
                ]);
            }
            else{
                return response()->json([
                    'status'=>400,
                    'message'=>'محصول مورد نظر در دسترس نیست '
                ]);
            }
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'دسته‌بندی مورد نظر یافت نشد'
            ]);
        }
    }
}
