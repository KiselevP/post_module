<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\PostsController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('posts', [PostsController::class, 'getAllPosts']);
Route::get('posts/{id}', [PostsController::class, 'getPostById']);

Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('posts', [PostsController::class, 'addNewPost']);
    Route::put('posts/{id}', [PostsController::class, 'updatePost']);
    Route::delete('posts/{id}', [PostsController::class, 'deletePost']);
    Route::post('refresh', [LoginController::class, 'refresh']);
});
