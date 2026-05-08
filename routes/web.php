<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MatchPredictionController;
use App\Http\Controllers\QuinielaMaestraController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('leaderboard'));

Route::middleware(['auth'])->group(function () {

    // Quiniela Maestra
    Route::get('/quiniela/maestra',  [QuinielaMaestraController::class, 'index'])->name('quiniela.maestra');
    Route::post('/quiniela/maestra', [QuinielaMaestraController::class, 'store'])->name('quiniela.maestra.store');

    // Partidos
    Route::get('/quiniela/partidos',          [MatchPredictionController::class, 'index'])->name('quiniela.partidos');
    Route::get('/quiniela/partidos/{match}',  [MatchPredictionController::class, 'show'])->name('quiniela.partidos.show');
    Route::post('/quiniela/partidos/{match}', [MatchPredictionController::class, 'store'])->name('quiniela.partidos.store');

    // Leaderboard
    Route::get('/tabla', [LeaderboardController::class, 'index'])->name('leaderboard');
});

// Admin
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                          [AdminController::class, 'index'])->name('index');
    Route::post('/users',                    [AdminController::class, 'createUser'])->name('users.create');
    Route::delete('/users/{user}',           [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/match/{match}/score',      [AdminController::class, 'scoreMatch'])->name('match.score');
    Route::post('/maestra/score',            [AdminController::class, 'scoreMaestra'])->name('maestra.score');
});

require __DIR__ . '/auth.php';
