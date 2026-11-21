<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Laravel Guest Post Management System
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
Route::get('/login', [AuthController::class, 'login'])->name('login');
