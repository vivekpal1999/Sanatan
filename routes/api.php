<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',  [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});

//Banner Route
Route::post('/banner', [BannerController::class, 'store_banner']);
Route::get('/get_all_banner', [BannerController::class, 'index']);
Route::put('/update_banner', [BannerController::class, 'update_banner']);
Route::delete('/banners/{id}', [BannerController::class, 'destroy_banner']);
