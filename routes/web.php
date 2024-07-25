<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Data Routes
Route::get('/data', [DataController::class, 'index'])->name('data.index');
Route::get('/data/create', [DataController::class, 'create'])->name('data.create');
Route::post('/data', [DataController::class, 'store'])->name('data.store');
Route::get('/data/print', [DataController::class, 'print'])->name('data.print'); 
Route::get('/data/{id}/edit', [DataController::class, 'edit'])->name('data.edit');
Route::put('/data/{id}', [DataController::class, 'update'])->name('data.update');
Route::delete('/data/{id}', [DataController::class, 'destroy'])->name('data.delete');

