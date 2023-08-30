<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::get('getCategory',[FrontendController::class, 'category']);
Route::get('fetchProducts/{slug}',[FrontendController::class, 'product']);
Route::get('viewProductDetail/{category_slug}/{product_slug}',[FrontendController::class, 'viewProduct']);

Route::post('add-to-cart',[CartController::class, 'addToCart']);
Route::get('cart',[CartController::class, 'viewCart']);
Route::put('cart-updateQuantity/{cart_id}/{scope}',[CartController::class, 'updateQuantity']);
Route::delete('delete-cartItem/{cart_id}',[CartController::class, 'deleteCart']);

Route::post('place-order',[CheckoutController::class, 'placeorder']);

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
    Route::put('/update-category/{id}',[CategoryController::class, 'update']);
    Route::delete('/delete-category/{id}',[CategoryController::class, 'destroy']);
    Route::get('/all-category',[CategoryController::class, 'allCategory']);

    //orders
    Route::get('admin/orders',[OrderController::class,'index']);

    //product
    Route::post('/store-product',[ProductController::class, 'store']);
    Route::get('/view-product',[ProductController::class, 'index']);
    Route::get('/edit-product/{id}',[ProductController::class, 'edit']);
    Route::post('/update-product/{id}',[ProductController::class, 'update']);

    //users
    Route::get('/view-users',[UserController::class , 'index']);
});
Route::middleware(['auth:sanctum'])->group(function (){
    
    Route::post('/logout', [AuthController::class , 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
