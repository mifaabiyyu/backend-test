<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
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

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/auth/logout', [AuthController::class, 'logout']);
       // Categories
       Route::prefix('category')->group(function () {
           Route::get('/get-data', [CategoryController::class, 'index']);
           Route::post('/post-data', [CategoryController::class, 'store']);
           Route::get('/detail-data/{id}', [CategoryController::class, 'show']);
           Route::post('/update-data/{id}', [CategoryController::class, 'update']);
           Route::delete('/delete-data/{id}', [CategoryController::class, 'destroy']);
           Route::get('/get-post', [CategoryController::class, 'get_post']);
       });

       // Posts
       Route::prefix('post')->group(function () {
           Route::get('/get-data', [PostController::class, 'index']);
           Route::post('/post-data', [PostController::class, 'store']);
           Route::get('/detail-data/{id}', [PostController::class, 'show']);
           Route::post('/update-data/{id}', [PostController::class, 'update']);
           Route::delete('/delete-data/{id}', [PostController::class, 'destroy']);
       });

       // Roles
       Route::prefix('roles')->middleware(['role:admin'])->group(function () {
           Route::get('/get-data', [RolesController::class, 'index']);
           Route::post('/post-data', [RolesController::class, 'store']);
           Route::get('/detail-data/{id}', [RolesController::class, 'show']);
           Route::post('/update-data/{id}', [RolesController::class, 'update']);
           Route::delete('/delete-data/{id}', [RolesController::class, 'destroy']);
       });

       // Users
       Route::prefix('users')->middleware(['role:admin'])->group(function () {
           Route::get('/get-data', [UsersController::class, 'index']);
           Route::post('/post-data', [UsersController::class, 'store']);
           Route::get('/detail-data/{id}', [UsersController::class, 'show']);
           Route::post('/update-data/{id}', [UsersController::class, 'update']);
           Route::delete('/delete-data/{id}', [UsersController::class, 'destroy']);
       });

});
