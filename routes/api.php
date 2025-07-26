<?php

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

use App\Http\Controllers\TodoController;
use App\Http\Controllers\DBproftestController;

Route::apiResource('todos', TodoController::class);

Route::patch('/todos/{id}/toggle-status', [TodoController::class, 'toggleStatus']);


Route::get('/dbproftest', [DBproftestController::class, 'index']);
Route::post('/dbproftest/generate', [DBprofTestController::class, 'generate']);
