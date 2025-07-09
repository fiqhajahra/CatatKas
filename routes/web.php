<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('layouts.guest');
});

// Routes yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions Routes
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/yearly', [ReportController::class, 'yearly'])->name('yearly');
        Route::get('/date-range', [ReportController::class, 'dateRange'])->name('date-range');
    });

    // Profile Routes (sudah ada dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('charts')->name('charts.')->group(function () {
        Route::get('/monthly', [ChartController::class, 'monthlyChart'])->name('monthly');
        Route::get('/yearly-trend', [ChartController::class, 'yearlyTrendChart'])->name('yearly-trend');
        Route::get('/distribution', [ChartController::class, 'distributionChart'])->name('distribution');
        Route::get('/summary-stats', [ChartController::class, 'summaryStats'])->name('summary-stats');
    });
});

// Auth Routes (sudah disediakan oleh Breeze)
require __DIR__ . '/auth.php';
