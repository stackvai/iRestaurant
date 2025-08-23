<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Menus\CategoryController;
use App\Http\Controllers\Menus\ImenusController;
use App\Http\Controllers\Menus\ItemController;
use App\Http\Controllers\Options\OptionController;
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
    Route::delete('/imenus/{menu}/categories/{category}', [CategoryController::class, 'destroy'])->name('imenus.categories.destroy');
    // Items
    Route::get('/imenus/{menu}/categories/{category}/add-item', [ItemController::class, 'create'])->name('imenus.categories.items.create');
    Route::post('/imenus/{menu}/categories/{category}/items', [ItemController::class, 'store'])->name('imenus.categories.items.store');
    Route::get('/imenus/{menu}/categories/{category}/items/{item}/edit', [ItemController::class, 'edit'])->name('imenus.categories.items.edit');
    Route::put('/imenus/{menu}/categories/{category}/items/{item}', [ItemController::class, 'update'])->name('imenus.categories.items.update');
    Route::delete('/imenus/{menu}/categories/{category}/items/{item}', [ItemController::class, 'destroy'])->name('imenus.categories.items.destroy');
    // Items reorder
    Route::post('/imenus/{menu}/categories/{category}/items/reorder', [ItemController::class, 'reorderItems'])
        ->name('imenus.categories.items.reorder');

    //Options
    Route::resource('options', OptionController::class);
    //Customers
    Route::resource("customers", CustomerController::class);
    // Staff export (CSV)
    Route::get('/staffs/export', [StaffController::class, 'export'])
        ->name('staffs.export');
    // Staff print (all data)
    Route::get('/staffs/print', [StaffController::class, 'print'])
        ->name('staffs.print');
    //Stuffs
    Route::resource("staffs", StaffController::class);
    //Roles
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{role}/permissions', [RoleAccessController::class, 'edit'])->name('roles.access.edit');
    Route::post('/roles/{role}/permissions', [RoleAccessController::class, 'update'])->name('roles.access.update');
    // Only allow access if the slug exists
    Route::match(['get', 'put'], '/pages/{slug}', [PageController::class, 'handle'])
        ->where('slug', '[a-zA-Z0-9\-]+')
        ->name('pages.handle');
    // Branch export (CSV)
    Route::get('/branches/export', [BranchController::class, 'export'])
        ->name('branches.export');
    // Branch print (all data)
    Route::get('/branches/print', [BranchController::class, 'print'])
        ->name('branches.print');
    // Branch resource routes
    Route::resource('branches', BranchController::class)->except(['show']);

    //pos
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/order', [POSController::class, 'store'])->name('pos.store');
});
