<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // cash
    Route::get('/cash/income-types', [PageController::class, 'cashIncomeTypes'])->name('cash.income-types');
    Route::get('/cash/incomes', [PageController::class, 'cashIncomes'])->name('cash.incomes');
    Route::get('/cash/expense-types', [PageController::class, 'cashExpenseTypes'])->name('cash.expense-types');
    Route::get('/cash/expenses', [PageController::class, 'cashExpenses'])->name('cash.expenses');

    // bank
    Route::get('/bank/incomes-types', [PageController::class, 'bankIncomeTypes'])->name('bank.income-types');
    Route::get('/bank/incomes', [PageController::class, 'bankIncomes'])->name('bank.incomes');
    Route::get('/bank/expense-types', [PageController::class, 'bankExpenseTypes'])->name('bank.expense-types');
    Route::get('/bank/expenses', [PageController::class, 'bankExpenses'])->name('bank.expenses');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
