<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\JournalEntryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;

// Note: this file is auto-registered under the "admin" prefix + name,
// with the web + auth + admin middleware, in RouteServiceProvider.

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ── Point of Sale ─────────────────────────────────────────────────────────
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::get('/pos/search', [PosController::class, 'search'])->name('pos.search');
Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.checkout');
Route::get('/pos/register', [PosController::class, 'register'])->name('pos.register');
Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');

// ── Products & Categories ────────────────────────────────────────────────
Route::resource('products', ProductController::class)->except(['show']);
Route::resource('categories', CategoryController::class)->except(['show']);

// ── Units of Measure ─────────────────────────────────────────────────────
Route::resource('units', UnitController::class)->except(['show']);

// ── Orders ───────────────────────────────────────────────────────────────
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

// ── Customers ────────────────────────────────────────────────────────────
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

// ── Vendors & Vendor Payments ────────────────────────────────────────────
Route::resource('vendors', VendorController::class);
Route::post('vendors/{vendor}/payments', [VendorController::class, 'storePayment'])->name('vendors.payments.store');
Route::delete('vendors/{vendor}/payments/{payment}', [VendorController::class, 'destroyPayment'])->name('vendors.payments.destroy');

// ── Purchase Orders ───────────────────────────────────────────────────────
Route::resource('purchase-orders', PurchaseOrderController::class)->except(['edit', 'update', 'destroy']);
Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

// ── Discount Codes ────────────────────────────────────────────────────────
Route::resource('discount-codes', DiscountCodeController::class)->except(['show']);

// ── Stock Management ─────────────────────────────────────────────────────
Route::get('stock', [StockController::class, 'index'])->name('stock.index');
Route::post('stock/{product}/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

// ── Warehouses ────────────────────────────────────────────────────────────
Route::resource('warehouses', WarehouseController::class);

// ── Stock Movements ───────────────────────────────────────────────────────
Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
Route::get('stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
Route::post('stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
Route::get('stock-movements/available', [StockMovementController::class, 'available'])->name('stock-movements.available');

// ── Expenses ──────────────────────────────────────────────────────────────
Route::resource('expenses', ExpenseController::class)->except(['show']);

// ── Accounts (Chart of Accounts) ─────────────────────────────────────────
Route::resource('accounts', AccountController::class)->except(['destroy']);
Route::delete('accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');

// ── Journal Entries ───────────────────────────────────────────────────────
Route::get('journal-entries', [JournalEntryController::class, 'index'])->name('journal-entries.index');
Route::get('journal-entries/create', [JournalEntryController::class, 'create'])->name('journal-entries.create');
Route::post('journal-entries', [JournalEntryController::class, 'store'])->name('journal-entries.store');
Route::get('journal-entries/{journalEntry}', [JournalEntryController::class, 'show'])->name('journal-entries.show');

// ── Financial Reports ─────────────────────────────────────────────────────
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
