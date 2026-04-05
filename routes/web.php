<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserHomeController;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return view('welcome');
})->name('home');

// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// User home page (after login)
Route::get('/user/home', function () {
    return view('user.home');
})->name('user.home');

// Registration page (both donor & receiver)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Login
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================== PROTECTED USER ROUTES (Requires Authentication) ====================

Route::middleware('auth')->group(function () {

    // User Home/Dashboard
    Route::get('/user/home', [UserHomeController::class, 'index'])->name('user.home');

    // Location management
    Route::post('/user/update-location', [UserHomeController::class, 'updateLocation'])->name('user.update.location');

    // Admin/Collection Center selection
    Route::post('/user/select-admin', [UserHomeController::class, 'selectAdmin'])->name('user.select.admin');
    Route::post('/user/clear-admin', [UserHomeController::class, 'clearAdmin'])->name('user.clear.admin');

    // Cloth management
    Route::get('/user/cloth/{id}', [UserHomeController::class, 'clothDetail'])->name('user.cloth.detail');
    Route::post('/user/request-cloth', [UserHomeController::class, 'requestCloth'])->name('user.request.cloth');
});

Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::post('/admin/{id}/photo', [AdminController::class, 'updatePhoto'])->name('admin.update.photo');
