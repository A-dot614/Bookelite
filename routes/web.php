<?php

use App\Http\Controllers\EcommerceController;
use App\Models\Ecommerce;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/about', [EcommerceController::class, 'about'])->name('about');
Route::get('/service', [EcommerceController::class, 'service'])->name('service');
Route::get('/contact', [EcommerceController::class, 'contact'])->name('contact');
Route::get('/', [EcommerceController::class, 'index'])->name('home');
Route::get('/detail/{shop:slug}', [EcommerceController::class, 'detail'])->name('detail');


// Dashboard (kept as-is)
Route::get('/dashboard',[AdminController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes: books, customers, reports (use controller + model binding where applicable)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('books', [AdminController::class,'index'])->name('books.index');
    Route::get('books/create', [AdminController::class,'form'])->name('books.create');
    Route::get('books/{shop:slug}', [AdminController::class,'carddetail'])->name('books.show');
    Route::get('customers', [AdminController::class,'customer'])->name('customers.index');
    Route::get('reports', [AdminController::class,'report'])->name('reports.index');
    Route::post('books', [AdminController::class,'postBooks'])->name('books.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
