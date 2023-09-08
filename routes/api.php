<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::get('getCategory',[FrontendController::class, 'category']);
Route::get('getCarousel',[FrontendController::class, 'carousel']);
Route::get('news-product',[FrontendController::class, 'newsProduct']);
Route::get('fetchProducts/{slug}',[FrontendController::class, 'product']);
Route::get('viewProductDetail/{category_slug}/{product_slug}',[FrontendController::class, 'viewProduct']);

Route::post('add-to-cart',[CartController::class, 'addToCart']);
Route::get('cart',[CartController::class, 'viewCart']);
Route::put('cart-updateQuantity/{cart_id}/{scope}',[CartController::class, 'updateQuantity']);
Route::delete('delete-cartItem/{cart_id}',[CartController::class, 'deleteCart']);

Route::post('place-order',[CheckoutController::class, 'placeorder']);

//profile
Route::get('profile',[ProfileController::class , 'index']);
Route::get('edit-profile',[ProfileController::class , 'edit']);
Route::post('update-profile',[ProfileController::class , 'update']);

Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function (){
    Route::get('checkingAuthenticated', function (){
       $user= auth()->user()->name;
        return response()->json([
            'message'=>'شما وارد شدید',
            'status'=>200,
            'user'=>$user
        ],200);
    });
    //category
    Route::post('/store-category',[CategoryController::class, 'store']);
    Route::get('/view-category',[CategoryController::class, 'index']);
    Route::get('/edit-category/{id}',[CategoryController::class, 'edit']);
    Route::post('/update-category/{id}',[CategoryController::class, 'update']);
    Route::delete('/delete-category/{id}',[CategoryController::class, 'destroy']);
    Route::get('/all-category',[CategoryController::class, 'allCategory']);

    //orders
    Route::get('admin/orders',[OrderController::class,'index']);
    Route::get('admin/view-order/{order_id}',[OrderController::class,'detail']);

    //product
    Route::post('/store-product',[ProductController::class, 'store']);
    Route::get('/view-product',[ProductController::class, 'index']);
    Route::get('/edit-product/{id}',[ProductController::class, 'edit']);
    Route::post('/update-product/{id}',[ProductController::class, 'update']);
    Route::delete('/delete-product/{id}',[ProductController::class, 'destroy']);

    //users
    Route::get('/view-users',[UserController::class , 'index']);
    Route::get('/edit-user/{id}',[UserController::class , 'edit']);
    Route::post('/update-user/{id}',[UserController::class , 'update']);
    Route::delete('/delete-user/{id}',[UserController::class , 'destroy']);

    //dashboard
    Route::get('sale-category',[DashboardController::class, 'sale_category']);
});
Route::middleware(['auth:sanctum'])->group(function (){
    
    Route::post('/logout', [AuthController::class , 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
