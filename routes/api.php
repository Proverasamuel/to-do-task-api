<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function () {
 Route::apiResource('tasks', TaskController::class);
    Route::get('tasks/status/{status}', [TaskController::class, 'filterByStatus']);
  
});
