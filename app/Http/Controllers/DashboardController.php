<?php

namespace App\Http\Controllers;


use App\Models\Orderitems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function sale_category(){
        $data=Orderitems::join('products', 'product_id', '=', 'products.id')
        ->join('categories', 'category_id', '=', 'categories.id')
        ->select('categories.name', DB::raw('SUM(Orderitems.price) as total_price'))
        ->groupBy('categories.name')->get();
        
       return response()->json([
        'status'=>200,
        'data'=>$data
       ]);                 
    }
}
