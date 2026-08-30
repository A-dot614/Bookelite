<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\SellerCheck;
use Illuminate\Support\Facades\Route;

// =========================================================================
// Public Storefront & Information Routes
// =========================================================================
Route::get('/', [EcommerceController::class, 'index'])->name('home');
Route::get('/detail/{ecommerce:slug}', [EcommerceController::class, 'detail'])->name('detail');
Route::get('/about', [EcommerceController::class, 'about'])->name('about');
Route::get('/service', [EcommerceController::class, 'service'])->name('service');
Route::get('/contact', [EcommerceController::class, 'contact'])->name('contact');

// =========================================================================
// Shopping Cart Routes (Session-based)
// =========================================================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{ecommerce:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart-clear', [CartController::class, 'clear'])->name('cart.clear');

// =========================================================================
// Checkout & Order Placement Routes
// =========================================================================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/{order}/success', [CheckoutController::class, 'success'])->name('checkout.success');

// =========================================================================
// Patron Account Routes (Orders & Wishlist)
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->seller) {
            return redirect()->route('seller.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{ecommerce:slug}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================================
// Seller Merchant Routes
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/seller/register', [SellerController::class, 'create'])->name('seller.register');
    Route::post('/seller/register', [SellerController::class, 'store'])->name('seller.store');
});

Route::prefix('seller')->middleware(['auth', SellerCheck::class])->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/books', [SellerController::class, 'books'])->name('books.index');
    Route::get('/books/create', [SellerController::class, 'createBook'])->name('books.create');
    Route::post('/books', [SellerController::class, 'storeBook'])->name('books.store');
    Route::get('/books/{ecommerce:slug}/edit', [SellerController::class, 'editBook'])->name('books.edit');
    Route::put('/books/{ecommerce:slug}', [SellerController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{ecommerce:slug}', [SellerController::class, 'destroyBook'])->name('books.destroy');
    Route::get('/orders', [SellerController::class, 'orders'])->name('orders.index');
});

// =========================================================================
// Admin Curator Studio Routes
// =========================================================================
Route::prefix('admin')->middleware(['auth', 'verified', AdminCheck::class])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/books', [AdminController::class, 'index'])->name('books.index');
    Route::get('/books/create', [AdminController::class, 'form'])->name('books.create');
    Route::post('/books', [AdminController::class, 'postBooks'])->name('books.store');
    Route::get('/books/{ecommerce:slug}', [AdminController::class, 'carddetail'])->name('books.show');
    Route::get('/books/{ecommerce:slug}/edit', [AdminController::class, 'edit'])->name('books.edit');
    Route::put('/books/{ecommerce:slug}', [AdminController::class, 'update'])->name('books.update');
    Route::delete('/books/{ecommerce:slug}', [AdminController::class, 'destroy'])->name('books.destroy');
    Route::get('/customers', [AdminController::class, 'customer'])->name('customers.index');
    Route::get('/reports', [AdminController::class, 'report'])->name('reports.index');
});

require __DIR__ . '/auth.php';
