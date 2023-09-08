<?php

use App\Models\Orderitems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $data=Orderitems::join('products', 'product_id', '=', 'products.id')
                        ->join('categories', 'category_id', '=', 'categories.id')
                        ->select('categories.name', DB::raw('SUM(Orderitems.price) as total_price'))
                        ->groupBy('categories.name')->get();
                        dd($data);
    // return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
