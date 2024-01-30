<?php

use App\Http\Controllers\auth\authController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;

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
//     return $request->user();
// });


Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::post('register', [authController::class, 'register']);
    Route::post('login', [authController::class, 'loginAdmin']);
    Route::post('login_ecommerce', [authController::class, 'loginEcommerce']);
    Route::get('profile', [authController::class, 'profile']);



    Route::post('refresh', [authController::class, 'refresh']);
    Route::get('/', [UserController::class,'getAlluser']);
    Route::get('/{id}', [UserController::class,'getUserById']);
});

Route::get('/', [authController::class, 'logout']);

