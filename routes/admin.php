<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Menus\CategoryController;
use App\Http\Controllers\Menus\ImenusController;
use App\Http\Controllers\Menus\ItemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    // Menus
    Route::resource('imenus', ImenusController::class);
    // Category nested under menu
    Route::get('/imenus/{menu}/add-category', [CategoryController::class, 'create'])->name('imenus.categories.create');
    Route::post('/imenus/{menu}/categories', [CategoryController::class, 'store'])->name('imenus.categories.store');
    Route::get('/imenus/{menu}/categories/{category}/edit', [CategoryController::class, 'edit'])->name('imenus.categories.edit');
    Route::put('/imenus/{menu}/categories/{category}', [CategoryController::class, 'update'])->name('imenus.categories.update');

    // Items
    Route::resource('items', ItemController::class);
});
