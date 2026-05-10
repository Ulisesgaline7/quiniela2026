<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MatchPredictionController;
use App\Http\Controllers\QuinielaMaestraController;
use App\Http\Controllers\SpecialEventsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('leaderboard'));

// ── Rutas PÚBLICAS (sin login — para compartir en WhatsApp) ──
Route::get('/resultados',              [\App\Http\Controllers\ResultsController::class, 'public'])->name('results.public');
Route::get('/resultados/jornada',      [\App\Http\Controllers\ResultsController::class, 'matchday'])->name('results.matchday');
Route::get('/resultados/jugador/{user}',[\App\Http\Controllers\ResultsController::class, 'player'])->name('results.player');

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

    // Eventos especiales
    Route::get('/quiniela/especiales',  [SpecialEventsController::class, 'index'])->name('quiniela.especiales');
    Route::post('/quiniela/especiales', [SpecialEventsController::class, 'store'])->name('quiniela.especiales.store');
});

// Admin
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                          [AdminController::class, 'index'])->name('index');
    Route::post('/users',                    [AdminController::class, 'createUser'])->name('users.create');
    Route::delete('/users/{user}',           [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/match/{match}/score',      [AdminController::class, 'scoreMatch'])->name('match.score');
    Route::post('/match/create',             [AdminController::class, 'createMatch'])->name('match.create');
    Route::post('/maestra/score',            [AdminController::class, 'scoreMaestra'])->name('maestra.score');
    Route::post('/especiales/{type}/resolve',[AdminController::class, 'resolveSpecialEvent'])->name('especiales.resolve');
});

require __DIR__ . '/auth.php';
