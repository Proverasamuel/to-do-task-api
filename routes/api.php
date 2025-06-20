<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login',[AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
 Route::apiResource('tasks', TaskController::class);
    Route::get('tasks/status/{status}', [TaskController::class, 'filterByStatus']);
  
});
