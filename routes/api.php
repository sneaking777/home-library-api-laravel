<?php

use App\Authors\Actions\CreateAuthorAction;
use App\Books\Actions\CreateBookAction;
use App\Books\Actions\DeleteBookAction;
use App\Books\Actions\ShowBookDetailAction;
use App\Books\Actions\UpdateBookAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum', 'api'])->prefix('v1')->group(function () {
    Route::prefix('book')->group(function () {
        Route::post('/', CreateBookAction::class)->name('book.store');
        Route::get('/{book}', ShowBookDetailAction::class)->name('book.show');
        Route::put('/{book}', UpdateBookAction::class)->name('book.update');
        Route::delete('/{book}', DeleteBookAction::class)->name('book.destroy');
    });

    Route::prefix('author')->group(function () {
        Route::post('/', CreateAuthorAction::class);
    });
});
