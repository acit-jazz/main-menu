<?php

use AcitJazz\MainMenu\Http\Controllers\Backend\MenuController;
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'auth.admin'])->prefix('backend')->group(function () {

    // Menu
    Route::post('/menu/{menu}/delete', [MenuController::class, 'delete'])->name('menu.delete')->middleware('admin_permission:Delete Menu');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index')->middleware('admin_permission:View Menu');
    Route::get('/menu/page', [MenuController::class, 'getPage'])->name('menu.page')->middleware('admin_permission:View Menu');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('menu.create')->middleware('admin_permission:Create Menu');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store')->middleware('admin_permission:Create Menu');

});