<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjetController;

// Route::get('/', [ProjetController::class, 'index'])->name('index');

// // Route::resource('projets', ProjetController::class);

// Route::prefix('projets/bo_nthnl_76')->name('projets.')->group(function() {
//     Route::get('create', [ProjetController::class, 'create'])->name('create');
//     Route::post('/', [ProjetController::class, 'store'])->name('store');
//     Route::get('{projet}/edit', [ProjetController::class, 'edit'])->name('edit');
//     Route::put('{projet}', [ProjetController::class, 'update'])->name('update');
//     Route::delete('{projet}', [ProjetController::class, 'destroy'])->name('destroy');
// });

// Front routes
Route::get('/', [ProjetController::class, 'index'])->name('index');
Route::get('projets/{projet}', [ProjetController::class, 'show'])->name('projets.show');

Route::prefix('projets/bo_nthnl_76')->name('projets.admin.')->group(function() {
    Route::get('create', [ProjetController::class, 'create'])->name('create');
    Route::post('/', [ProjetController::class, 'store'])->name('store');
    Route::get('{projet}/edit', [ProjetController::class, 'edit'])->name('edit');
    Route::put('{projet}', [ProjetController::class, 'update'])->name('update');
    Route::delete('{projet}', [ProjetController::class, 'destroy'])->name('destroy');
});
