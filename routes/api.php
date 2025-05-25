<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController; // Added for User Auth
use App\Http\Controllers\Api\AdminAuthController; // Added for Admin Auth
use App\Http\Controllers\Api\BookController; // Added for Book CRUD
use App\Http\Controllers\Api\WebContentController; // Added for WebContent CRUD

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

// Book Routes (Protected by auth:api and specific permissions)
Route::group(['prefix' => 'books', 'middleware' => ['auth:api']], function () {
    Route::get('/', [BookController::class, 'index'])->middleware('permission:view books');
    Route::post('/', [BookController::class, 'store'])->middleware('permission:create books');
    Route::get('/{book}', [BookController::class, 'show'])->middleware('permission:view books');
    Route::put('/{book}', [BookController::class, 'update'])->middleware('permission:edit books');
    Route::patch('/{book}', [BookController::class, 'update'])->middleware('permission:edit books'); // Also common
    Route::delete('/{book}', [BookController::class, 'destroy'])->middleware('permission:delete books');
});

// WebContent Routes (Protected by auth:api and specific permissions)
Route::group(['prefix' => 'web-content', 'middleware' => ['auth:api']], function () {
    Route::get('/', [WebContentController::class, 'index'])->middleware('permission:view web_content');
    Route::post('/', [WebContentController::class, 'store'])->middleware('permission:create web_content');
    Route::get('/{webContent}', [WebContentController::class, 'show'])->middleware('permission:view web_content');
    Route::put('/{webContent}', [WebContentController::class, 'update'])->middleware('permission:edit web_content');
    Route::patch('/{webContent}', [WebContentController::class, 'update'])->middleware('permission:edit web_content');
    Route::delete('/{webContent}', [WebContentController::class, 'destroy'])->middleware('permission:delete web_content');
});

// Admin WebContent Routes (Protected by auth:admins and specific permissions)
Route::group(['prefix' => 'admin/web-content', 'middleware' => ['auth:admins']], function () {
    Route::get('/', [WebContentController::class, 'index'])->middleware('permission:view web_content,admins');
    Route::post('/', [WebContentController::class, 'store'])->middleware('permission:create web_content,admins');
    Route::get('/{webContent}', [WebContentController::class, 'show'])->middleware('permission:view web_content,admins');
    Route::put('/{webContent}', [WebContentController::class, 'update'])->middleware('permission:edit web_content,admins');
    Route::patch('/{webContent}', [WebContentController::class, 'update'])->middleware('permission:edit web_content,admins');
    Route::delete('/{webContent}', [WebContentController::class, 'destroy'])->middleware('permission:delete web_content,admins');
});
