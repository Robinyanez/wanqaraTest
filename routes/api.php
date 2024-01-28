<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\RecordController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'authToken']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('/weather',WeatherController::class)->only([
        'index','store','show'
    ])->names([
        'index'     => 'weather.index',
        'store'     => 'weather.store',
        'show'      => 'weather.show',
    ]);

    Route::post('/weather/comment', [CommentController::class, 'storeWeather']);

    Route::apiResource('/record',RecordController::class)->only([
        'index','store','show'
    ])->names([
        'index'     => 'record.index',
        'store'     => 'record.store',
        'show'      => 'record.show',
    ]);

    Route::post('/record/comment', [CommentController::class, 'storeRecord']);
});
