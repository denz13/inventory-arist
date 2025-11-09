<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\inventory\InventoryController;
use App\Http\Controllers\ledger\LedgerController;
use App\Http\Controllers\customer\CustomerController;
// use App\Http\Controllers\PageController;
// use App\Http\Controllers\DarkModeController;
// use App\Http\Controllers\ColorSchemeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');
// Route::get('color-scheme-switcher/{color_scheme}', [ColorSchemeController::class, 'switch'])->name('color-scheme-switcher');

// Debug route to test authentication
Route::get('/debug/auth', function() {
    return response()->json([
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});

Route::controller(AuthController::class)->middleware('loggedin')->group(function() {
    Route::get('login', 'loginView')->name('login.index');
    Route::post('login', 'login')->name('login.check');
    Route::get('debug/users', 'debugUsers')->name('debug.users'); // Temporary - remove in production
});

Route::middleware('auth')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    
    // Quantity-specific routes
    Route::get('inventory/quantity/{id}/edit', [InventoryController::class, 'editQuantity'])->name('inventory.quantity.edit');
    Route::put('inventory/quantity/{id}', [InventoryController::class, 'updateQuantity'])->name('inventory.quantity.update');
    Route::delete('inventory/quantity/{id}', [InventoryController::class, 'destroyQuantity'])->name('inventory.quantity.destroy');
    Route::get('ledger', [App\Http\Controllers\ledger\LedgerController::class, 'index'])->name('ledger.index');
    Route::get('ledger/{id}', [App\Http\Controllers\ledger\LedgerController::class, 'show'])->name('ledger.show');

    // Customer-specific routes
    Route::get('customer', [App\Http\Controllers\customer\CustomerController::class, 'index'])->name('customer.index');
    Route::post('customer', [App\Http\Controllers\customer\CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/{id}/edit', [App\Http\Controllers\customer\CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/{id}', [App\Http\Controllers\customer\CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customer/{id}', [App\Http\Controllers\customer\CustomerController::class, 'destroy'])->name('customer.destroy');
    
    // Customer Order routes
    Route::post('customer/order', [App\Http\Controllers\customer\CustomerController::class, 'storeOrder'])->name('customer.order.store');
    Route::get('customer/order/{id}/edit', [App\Http\Controllers\customer\CustomerController::class, 'editOrder'])->name('customer.order.edit');
    Route::put('customer/order/{id}', [App\Http\Controllers\customer\CustomerController::class, 'updateOrder'])->name('customer.order.update');
    Route::delete('customer/order/{id}', [App\Http\Controllers\customer\CustomerController::class, 'destroyOrder'])->name('customer.order.destroy');

    // Categories routes
    Route::get('categories', [App\Http\Controllers\categories\CategoriesController::class, 'index'])->name('categories.categories');
    Route::post('categories', [App\Http\Controllers\categories\CategoriesController::class, 'store'])->name('categories.store');
    Route::get('categories/{id}/edit', [App\Http\Controllers\categories\CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [App\Http\Controllers\categories\CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [App\Http\Controllers\categories\CategoriesController::class, 'destroy'])->name('categories.destroy');

    // Reports routes
    Route::get('reports/order', [App\Http\Controllers\reports\ReportController::class, 'orderReports'])->name('reports.order');
    Route::get('reports/order/{id}/print', [App\Http\Controllers\reports\ReportController::class, 'printOrder'])->name('reports.order.print');
    Route::get('reports/orders/print-multiple', [App\Http\Controllers\reports\ReportController::class, 'printMultipleOrders'])->name('reports.orders.print-multiple');
    Route::get('reports', [App\Http\Controllers\reports\ReportController::class, 'index'])->name('reports.index');
});
