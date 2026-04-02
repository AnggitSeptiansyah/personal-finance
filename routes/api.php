<?php

use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\BankExpenseController;
use App\Http\Controllers\Api\BankIncomeController;
use App\Http\Controllers\Api\CashExpenseController;
use App\Http\Controllers\Api\CashExpenseTypeController;
use App\Http\Controllers\Api\CashIncomeController;
use App\Http\Controllers\Api\CashIncomeTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function() {
    // cash
    Route::apiResource('cash/income-types', CashIncomeTypeController::class)->parameters(['income-types' => 'cashIncomeType']);
    Route::apiResource('cash/incomes', CashIncomeController::class)->parameters(['incomes' => 'cashIncome']);
    Route::apiResource('cash/expense-types', CashExpenseTypeController::class)->parameters(['expense-types' => 'cashExpenseType']);
    Route::apiResource('cash/expenses', CashExpenseController::class)->parameters(['expenses' => 'cashExpenses']);

    // bank account
    Route::apiResource('bank/accounts', BankAccountController::class)->parameters(['accounts' => 'bankAccount'])->only(['index', 'store', 'update', 'destroy']);

    // bank
    Route::apiResource('bank/income-types', BankIncomeTypeController::class)->parameters(['income-types' => 'bankIncomeType']);
    Route::apiResource('bank/incomes', BankIncomeController::class)->parameters(['incomes' => 'bankIncome']);
    Route::apiResource('bank/expense-types', BankExpenseController::class)->parameters(['expense-types' => 'bankExpenseType']);
    Route::apiResource('bank/expenses', BankExpenseController::class)->parameters(['expenses' => 'bankExpense']);
});