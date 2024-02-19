<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShapeController;
use App\Http\Controllers\AuthController;

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
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:sanctum']);

// Define routes without middleware
Route::get('shapes', [ShapeController::class, 'index']);

// Define routes with middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('shapes', ShapeController::class)->except(['index']);
});

