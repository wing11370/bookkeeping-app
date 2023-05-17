<?php

use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('records')->group(function () {
    Route::post('/', [RecordController::class, 'create']);
    Route::get('/', [RecordController::class, 'list']);
    Route::get('/{id}', [RecordController::class, 'find']);
    Route::put('/{id}', [RecordController::class, 'update']);
    Route::delete('/{id}', [RecordController::class, 'delete']);
});
