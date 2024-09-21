<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GamingRegister;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MysqlTestController;
use App\Http\Controllers\GamePage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('dashboard');
Route::get('/getgames', [MysqlTestController::class, 'apiTest'])->name('gamesTest');

Route::get('/registeruser', [GamingRegister::class, 'viewReg'])->name('gotoRegister');

Route::get('/game/{id}', [GamePage::class, 'viewGamePage'])->name('viewGamePage');

Route::post('/findgame', [GamePage::class, 'onSearchGameList'])->name('gameSearch');
Route::post('/sendReview', [GamePage::class, 'onSendReview'])->name('sendReview');
Route::post('/sendRating', [GamePage::class, 'onSendRating'])->name('sendRating');

require __DIR__.'/auth.php';
