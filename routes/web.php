<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductMasterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\TermsConditionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Serve images - using different route to avoid conflicts
Route::get('/get-image/{path}', function ($path) {
    // Get the absolute path to storage folder
    $basePath = base_path('storage/app/public/');
    $file = $basePath . $path;

    // Prevent directory traversal attacks
    if (realpath($file) === false || strpos(realpath($file), realpath($basePath)) !== 0) {
        abort(404);
    }

    if (!file_exists($file) || !is_file($file)) {
        abort(404);
    }

    // Read and stream file directly
    $content = file_get_contents($file);
    $mimeType = 'application/octet-stream';

    // Detect MIME type by extension
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'pdf' => 'application/pdf'
    ];

    if (isset($mimeTypes[$ext])) {
        $mimeType = $mimeTypes[$ext];
    }

    return response($content, 200, [
        'Content-Type' => $mimeType,
        'Content-Length' => strlen($content),
        'Cache-Control' => 'public, max-age=86400'
    ]);
})->where('path', '.*')->name('storage.file');

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout route (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Change Password Routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
});

// Protected Routes - Require Authentication
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/import/form', [CustomerController::class, 'importForm'])->name('customers.import-form');
    Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::get('/customers/download/template', [CustomerController::class, 'downloadTemplate'])->name('customers.download-template');
    Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/trash/list', [CustomerController::class, 'trash'])->name('customers.trash');
    Route::post('/customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::post('/customers/trash/bulk-restore', [CustomerController::class, 'bulkRestore'])->name('customers.bulkRestore');
    Route::delete('/customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.forceDelete');
    Route::delete('/customers/trash/bulk-delete', [CustomerController::class, 'bulkForceDelete'])->name('customers.bulkForceDelete');

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

    // Product Master Routes (main product) - MUST BE LAST to avoid conflicts
    // Using explicit prefix to separate from product details routes
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/', [ProductMasterController::class, 'index'])->name('index');
        Route::get('/create', [ProductMasterController::class, 'create'])->name('create');
        Route::post('/', [ProductMasterController::class, 'store'])->name('store');
        // CSV Import Routes - MUST BE BEFORE wildcard routes
        Route::get('/import/form', [ProductMasterController::class, 'importForm'])->name('import-form');
        Route::post('/import', [ProductMasterController::class, 'import'])->name('import');
        // Detail routes - AFTER specific routes
        Route::get('/{productMaster}/show', [ProductMasterController::class, 'show'])->name('show');
        Route::get('/{productMaster}/edit', [ProductMasterController::class, 'edit'])->name('edit');
        Route::put('/{productMaster}', [ProductMasterController::class, 'update'])->name('update');
        Route::delete('/{productMaster}', [ProductMasterController::class, 'destroy'])->name('destroy');
    });

    // Product Details Routes (nested under products) - DEFINED FIRST
    Route::prefix('products')->name('products.')->group(function () {
        // Specific routes BEFORE wildcard routes
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/list', [ProductController::class, 'index'])->name('list');  // For backward compatibility
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/master/{productMaster}/items', [ProductController::class, 'getByMaster'])->name('by-master');
        Route::get('/master/{productMaster}/template', [ProductController::class, 'getTemplateByMaster'])->name('template');
        Route::delete('/master/{productMaster}/model/{modelName}', [ProductController::class, 'destroyModel'])->name('destroy-model');

        // Wildcard routes LAST
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Quotation Routes
    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('/quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    Route::get('/quotations/trash/list', [QuotationController::class, 'trash'])->name('quotations.trash');
    Route::post('/quotations/{id}/restore', [QuotationController::class, 'restore'])->name('quotations.restore');
    Route::post('/quotations/trash/bulk-restore', [QuotationController::class, 'bulkRestore'])->name('quotations.bulkRestore');
    Route::delete('/quotations/{id}/force-delete', [QuotationController::class, 'forceDelete'])->name('quotations.forceDelete');
    Route::delete('/quotations/trash/bulk-delete', [QuotationController::class, 'bulkForceDelete'])->name('quotations.bulkForceDelete');
    Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'generatePdf'])->name('quotations.pdf');
    Route::get('/quotations/{quotation}/pdf/stream', [QuotationController::class, 'streamPdf'])->name('quotations.pdf.stream');
    Route::get('/quotations/{quotation}/pdf2', [QuotationController::class, 'generatePdf2'])->name('quotations.pdf2');
    Route::get('/quotations/{quotation}/pdf2/stream', [QuotationController::class, 'streamPdf2'])->name('quotations.pdf2.stream');

    // API routes for AJAX
    Route::get('/api/customers/search', [QuotationController::class, 'searchCustomers'])->name('api.customers.search');
    Route::get('/api/products/search', [QuotationController::class, 'searchProducts'])->name('api.products.search');
    Route::get('/api/products/types', [QuotationController::class, 'getProductTypes'])->name('api.products.types');
});
