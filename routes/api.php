<?php

use App\Books\Actions\CreateBookAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('api')->prefix('v1')->group(function () {
    Route::prefix('book')->group(function () {
        Route::post('/create', CreateBookAction::class);
    });
});
