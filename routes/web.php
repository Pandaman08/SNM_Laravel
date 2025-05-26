<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', [UserController::class, 'index'])->name('login.index');
Route::post('/', [UserController::class, 'login'])->name('login');
Route::get('/home', [AdminController::class, 'index'])->name('home');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/buscar', [UserController::class, 'showUser'])->name('users.buscar');
   Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
//Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');