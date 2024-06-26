<?php

use App\Http\Controllers\Admin\FieldController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\OrderProductController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\FildController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\DiscountProductController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController as APIProductController;
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::put('/customers/{id}/role', [AuthController::class, 'updateRole']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');
Route::get('/post/list', [PostController::class, 'index'])->middleware('auth:sanctum');

Route::get('fields/list', [FildController::class,'index'])->name('field.list');
Route::post('field/create', [FildController::class,'store'])->name('field.create');
Route::get('field/show/{id}', [FildController::class,'show'])->name('field.show');
Route::put('field/update/{id}', [FildController::class,'update'])->name('field.update');
Route::delete('field/delete/{id}', [FildController::class,'destroy'])->name('field.delete');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('orders/list', [OrderProductController::class, 'index']);
    Route::post('orders/create', [OrderProductController::class, 'store']);
    Route::get('orders/show/{id}', [OrderProductController::class, 'show']);
    Route::delete('orders/cancel/{id}', [OrderProductController::class, 'cancel']);
    Route::put('orders/reactivate/{id}', [OrderProductController::class, 'reactivate']);
});


//Booking
Route::get('/booking/list', [BookingController::class, 'index']);
Route::post('/booking/create', [BookingController::class, 'store']);
Route::get('/booking/show/{id}', [BookingController::class, 'show']);
Route::put('/booking/accept/{id}', [BookingController::class, 'acceptBooking']);
Route::put('/booking/reject/{id}', [BookingController::class, 'rejectBooking']);
Route::put('/booking/cancel/{id}', [BookingController::class, 'cancelBooking']);

//feedback
Route::apiResource('feedback', FeedbackController::class);

// Categories API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/category/list', [CategoryController::class, 'index']);
    
    Route::post('/category/create', [CategoryController::class, 'store']);
    Route::put('/category/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy']);
});
Route::get('/category/show/{id}', [CategoryController::class, 'show']);

// Products API Routes
Route::get('/product/list', [APIProductController::class, 'index']);
Route::post('/product/create', [APIProductController::class, 'store'])->middleware('auth:sanctum');
Route::put('/product/update/{id}', [APIProductController::class, 'update'])->middleware('auth:sanctum');
Route::get('/product/show/{id}', [APIProductController::class, 'show']);
Route::delete('/product/delete/{id}', [APIProductController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/sizes', [SizeController::class, 'index']);
Route::get('/colors', [ColorController::class, 'index']);


Route::get('/discount/list',[DiscountProductController::class,'index'])->name('discount.list');
Route::post('/discount/create',[DiscountProductController::class,'store'])->name('discount.create');
Route::get('/discount/show/{id}',[DiscountProductController::class,'show'])->name('discount.show');
Route::put('/discount/update/{id}',[DiscountProductController::class,'update'])->name('discount.update');
Route::delete('/discount/delete/{id}',[DiscountProductController::class,'destroy'])->name('discount.destroy');

