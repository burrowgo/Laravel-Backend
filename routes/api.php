<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController; // Added for User Auth
use App\Http\Controllers\Api\AdminAuthController; // Added for Admin Auth

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

// User Authentication Routes
Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);

// Admin Authentication Routes
Route::post('/admin/register', [AdminAuthController::class, 'register']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Protected User Route
Route::middleware('auth:api')->get('/user/profile', function (Request $request) {
    return $request->user();
});

// Protected Admin Route
Route::middleware('auth:admins')->get('/admin/dashboard', function (Request $request) {
    return $request->user(); // This will be the authenticated admin instance
});
