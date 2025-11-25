<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/places.geojson', [WelcomeController::class, 'geoJson'])->name('places.geojson');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/places', [AdminController::class, 'index'])->name('places.index');
    Route::get('/places/create', [AdminController::class, 'create'])->name('places.create');
    Route::post('/places', [AdminController::class, 'store'])->name('places.store');
    Route::get('/places/{place}/edit', [AdminController::class, 'edit'])->name('places.edit');
    Route::put('/places/{place}', [AdminController::class, 'update'])->name('places.update');
    Route::delete('/places/{place}', [AdminController::class, 'destroy'])->name('places.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
