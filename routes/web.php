<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GamingRegister;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MysqlTestController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\GamePage;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
});

Route::get('/home', [HomeController::class, 'viewHome'])->name('home');
Route::get('/', [HomeController::class, 'viewHome'])->name('home');
Route::get('/getgames', [MysqlTestController::class, 'apiTest'])->name('gamesTest');

Route::get('/registeruser', [GamingRegister::class, 'viewReg'])->name('gotoRegister');

Route::get('/game/{id}', [GamePage::class, 'viewGamePage'])->name('viewGamePage');
Route::get('/profile/{id}', [UserProfileController::class, 'viewProfile'])->name('viewProfile');

Route::post('/findgame', [GamePage::class, 'onSearchGameList'])->name('gameSearch');
Route::post('/sendReview', [GamePage::class, 'onSendReview'])->name('sendReview');
Route::post('/sendRating', [GamePage::class, 'onSendRating'])->name('sendRating');

Route::get('/search-games', [NavigationController::class, 'getGamesNav']);
Route::get('/search', [NavigationController::class, 'searchPage'])->name('search');;

require __DIR__.'/auth.php';
