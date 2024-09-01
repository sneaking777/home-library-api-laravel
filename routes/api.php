<?php

use App\Auth\Actions\ForgotPasswordAction;
use App\Auth\Actions\LoginAction;
use App\Auth\Actions\LogoutAction;
use App\Auth\Actions\RegisterAction;
use App\Auth\Actions\ResetPasswordAction;
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


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', LoginAction::class)->name('auth.login');
        Route::post('/register', RegisterAction::class)->name('auth.register');
        Route::delete('/logout', LogoutAction::class)->name('auth.logout')
            ->middleware('auth:sanctum');
        Route::post('/forgot-password', ForgotPasswordAction::class)->name('auth.forgot');
        Route::post('/reset-password', ResetPasswordAction::class)->name('auth.reset');
    });

    Route::prefix('book')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::post('/', CreateBookAction::class)->name('book.store');
            Route::get('/{book}', ShowBookDetailAction::class)->name('book.show');
            Route::put('/{book}', UpdateBookAction::class)->name('book.update');
            Route::delete('/{book}', DeleteBookAction::class)->name('book.destroy');
        });

    Route::prefix('author')->middleware('auth:sanctum')->group(function () {
        Route::post('/', CreateAuthorAction::class)->name('author.store');
    });
});
