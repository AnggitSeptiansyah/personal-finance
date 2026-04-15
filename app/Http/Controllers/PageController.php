<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    // dashboard
    public function dashboard(): View
    {
        return view('dashboard');
    }

    // Cash pages
    public function cashIncomeTypes(): View { return view('cash.income-types.index'); }
    public function cashIncomes(): View { return view('cash.incomes.index'); }
    public function cashExpenseTypes(): View { return view('cash.expense-types.index'); }
    public function cashExpenses(): View { return view('cash.expenses.index'); }

    // Bank
    public function bankAccounts(): View     { return view('bank.accounts.index'); }
    public function bankIncomeTypes(): View { return view('bank.income-types.index'); }
    public function bankIncomes(): View { return view('bank.incomes.index'); }
    public function bankExpenseTypes(): View { return view('bank.expense-types.index'); }
    public function bankExpenses(): View { return view('bank.expenses.index'); }
}
