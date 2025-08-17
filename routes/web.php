<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\inventory\InventoryController;
use App\Http\Controllers\ledger\LedgerController;
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
    Route::get('ledger', [App\Http\Controllers\ledger\LedgerController::class, 'index'])->name('ledger.index');
    Route::get('ledger/{id}', [App\Http\Controllers\ledger\LedgerController::class, 'show'])->name('ledger.show');
});
