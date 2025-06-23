<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CryptoTransactionController;

use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

//* Ruta de inicio *//
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

//* Ruta de lista de seguimiento *//
Route::get('/watchlist', function () {
    return 'Página de watchlist';
})->middleware(['auth'])->name('watchlist.index');

//* Rutas de cartera -> solo usuarios pro *//
Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/create', [WalletController::class, 'create'])->name('wallet.create');
    Route::post('/wallet', [WalletController::class, 'store'])->name('wallet.store');
    Route::get('/wallet/moves', [WalletController::class, 'show'])->name('wallet.moves');
    //* Rutas de operaciones *//
    Route::get('/wallet/transaction/create', [CryptoTransactionController::class, 'create'])->name('wallet.transaction.create');
    Route::post('/wallet/transaction', [CryptoTransactionController::class, 'store'])->name('wallet.transaction.store');
});

//* Ruta de busqueda de criptomonedas *//
Route::get('/buscar-crypto', [CryptoController::class, 'search'])
    ->name('crypto.search')
    ->middleware('auth');

//* Ruta de detalles cryptomonedas *//
Route::get('/home/{uuid}', [CryptoController::class, 'show'])
    ->middleware('auth')
    ->name('crypto.show');

//* Ruta de autocompletado *//
Route::get('/crypto/autocomplete', [CryptoController::class, 'autocomplete'])
    ->middleware('auth')
    ->name('crypto.autocomplete');

//* Rutas de watchlist *//
Route::middleware(['auth'])->group(function () {
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{watchlist}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
});

//* Rutas del Pricing *//
Route::get('/pricing', [PricingController::class, 'show'])->name('pricing.index');
Route::post('/start-trial', [PricingController::class, 'startTrial'])->middleware('auth')->name('start.trial');

//* Ruta precio/fecha *//
Route::get('/coin/price', [CryptoController::class, 'getPrice'])->name('coin.price');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
