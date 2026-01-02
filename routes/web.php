<?php

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductMasterController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\TermsConditionController;
use Illuminate\Support\Facades\Route;

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout route (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes - Require Authentication
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

    // Terms & Conditions Routes
    Route::get('/terms-conditions', [TermsConditionController::class, 'index'])->name('terms-conditions.index');
    Route::get('/terms-conditions/create', [TermsConditionController::class, 'create'])->name('terms-conditions.create');
    Route::post('/terms-conditions', [TermsConditionController::class, 'store'])->name('terms-conditions.store');
    Route::get('/terms-conditions/{termsCondition}/edit', [TermsConditionController::class, 'edit'])->name('terms-conditions.edit');
    Route::put('/terms-conditions/{termsCondition}', [TermsConditionController::class, 'update'])->name('terms-conditions.update');
    Route::delete('/terms-conditions/{termsCondition}', [TermsConditionController::class, 'destroy'])->name('terms-conditions.destroy');

    // Accessories Routes
    Route::get('/accessories', [AccessoryController::class, 'index'])->name('accessories.index');
    Route::get('/accessories/create', [AccessoryController::class, 'create'])->name('accessories.create');
    Route::post('/accessories', [AccessoryController::class, 'store'])->name('accessories.store');
    Route::get('/accessories/{accessory}/edit', [AccessoryController::class, 'edit'])->name('accessories.edit');
    Route::put('/accessories/{accessory}', [AccessoryController::class, 'update'])->name('accessories.update');
    Route::delete('/accessories/{accessory}', [AccessoryController::class, 'destroy'])->name('accessories.destroy');

    Route::get('/products', [ProductMasterController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductMasterController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductMasterController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductMasterController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductMasterController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductMasterController::class, 'destroy'])->name('products.destroy');

    // Quotation Routes
    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'generatePdf'])->name('quotations.pdf');
    Route::get('/quotations/{quotation}/pdf/stream', [QuotationController::class, 'streamPdf'])->name('quotations.pdf.stream');
    Route::get('/quotations/{quotation}/pdf2', [QuotationController::class, 'generatePdf2'])->name('quotations.pdf2');
    Route::get('/quotations/{quotation}/pdf2/stream', [QuotationController::class, 'streamPdf2'])->name('quotations.pdf2.stream');

    // API routes for AJAX
    Route::get('/api/customers/search', [QuotationController::class, 'searchCustomers'])->name('api.customers.search');
    Route::get('/api/products/search', [QuotationController::class, 'searchProducts'])->name('api.products.search');
    Route::get('/api/products/types', [QuotationController::class, 'getProductTypes'])->name('api.products.types');
});
