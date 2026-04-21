<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DonationManageController;
use App\Http\Controllers\Admin\RequestManageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EsewaController;
use App\Http\Controllers\KhaltiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\UserProfileController;
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

    Route::get('/profile', [UserProfileController::class, 'edit'])->name('user.profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/location', [UserProfileController::class, 'updateLocation'])->name('user.location.update');
    Route::post('/profile/password', [UserProfileController::class, 'changePassword'])
        ->name('user.password.change');

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
    Route::get('/category/{type}', [UserHomeController::class, 'categoryPage'])->name('user.category');

    // Searching
    Route::get('/user/search', [UserHomeController::class, 'search'])->name('user.search');
    Route::get('/user/recent-searches', [UserHomeController::class, 'getRecentSearches'])->name('user.recent-searches');
    Route::post('/user/refresh-recommendations', [UserHomeController::class, 'refreshRecommendations'])->name('user.refresh-recommendations');

    // Donation routes
    Route::get('/donate', [DonationController::class, 'create'])->name('user.donate');
    Route::post('/donate', [DonationController::class, 'store'])->name('user.donate.store');
    Route::get('/my-donations', [DonationController::class, 'myDonations'])->name('user.my-donations');
    Route::get('/donation/{id}', [DonationController::class, 'show'])->name('user.donation.show');

    // Cart routes
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Request routes
    Route::post('/request', [RequestController::class, 'store'])->name('user.request.store');
    Route::get('/my-requests', [RequestController::class, 'myRequests'])->name('user.my-requests');
    Route::get('/request/{id}', [RequestController::class, 'show'])->name('user.request.show');
    Route::post('/request/{id}/cancel', [RequestController::class, 'cancel'])->name('user.request.cancel');

    // Esewa and Khalti
    Route::get('/donate-money', function () {
        return view('user.donate-money');
    })->name('user.donate-money');

    // Esewa
    Route::post('/esewa/store', [EsewaController::class, 'store'])->name('esewa.store');
    Route::get('/esewa/pay', [EsewaController::class, 'pay'])->name('esewa.pay');
    Route::get('/esewa/success', [EsewaController::class, 'success'])->name('esewa.success');
    Route::get('/esewa/failure', [EsewaController::class, 'failure'])->name('esewa.failure');

    // Khalti
    Route::post('/khalti/initiate', [KhaltiController::class, 'initiate']);
    Route::get('/khalti/verify', [KhaltiController::class, 'verify'])->name('khalti.verify');
    Route::get('/khalti/success', [KhaltiController::class, 'khaltiSuccess'])->name('khalti.success');
    Route::get('/khalti/failure', [KhaltiController::class, 'khaltiFailure'])->name('khalti.failure');
});

Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::post('/admin/{id}/photo', [AdminController::class, 'updatePhoto'])->name('admin.update.photo');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest admin routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

        // Donation management
        Route::get('/donations', [DonationManageController::class, 'index'])->name('donations.index');
        Route::get('/donations/{id}', [DonationManageController::class, 'show'])->name('donations.show');
        Route::post('/donations/{id}/approve', [DonationManageController::class, 'approve'])->name('donations.approve');
        Route::post('/donations/{id}/reject', [DonationManageController::class, 'reject'])->name('donations.reject');
        Route::post('/donations/{id}/processing', [DonationManageController::class, 'processing'])->name('donations.processing');
        Route::post('/donations/{id}/complete', [DonationManageController::class, 'complete'])->name('donations.complete');

        // Request Management Routes
        Route::get('/requests', [RequestManageController::class, 'index'])->name('requests.index');
        Route::get('/requests/{id}', [RequestManageController::class, 'show'])->name('requests.show');
        Route::post('/requests/{id}/approve', [RequestManageController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [RequestManageController::class, 'reject'])->name('requests.reject');
        Route::post('/requests/{id}/complete', [RequestManageController::class, 'complete'])->name('requests.complete');
        Route::post('/requests/bulk-approve', [RequestManageController::class, 'bulkApprove'])->name('requests.bulk-approve');
        Route::get('/requests/filter/{status}', [RequestManageController::class, 'filter'])->name('requests.filter');
        Route::post('/requests/search', [RequestManageController::class, 'search'])->name('requests.search');

        // Category Management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{index}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{index}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});
