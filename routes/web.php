<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CryptoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//* Página de inicio *//
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

//* Página de lista de seguimiento *//
Route::get('/watchlist', function () {
    return 'Página de watchlist';
})->middleware(['auth'])->name('watchlist.index');

//* Página de cartera -> solo usuarios pro *//
Route::get('/wallet', function () {
    return 'Página de cartera';
})->middleware(['auth'])->name('wallet.index');

//* Página de busqueda de criptomonedas *//
Route::get('/buscar-crypto', [CryptoController::class, 'search'])
    ->name('crypto.search')
    ->middleware('auth');

//* Página de detalles cryptomonedas *//
Route::get('/home/{uuid}', [CryptoController::class, 'show'])
    ->middleware('auth')
    ->name('crypto.show');

//* Ruta de autocompletado *//
Route::get('/crypto/autocomplete', [CryptoController::class, 'autocomplete'])
    ->middleware('auth')
    ->name('crypto.autocomplete');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
