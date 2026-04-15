<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankExpense;
use App\Models\BankIncome;
use App\Models\CashExpense;
use App\Models\CashIncome;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request): JsonResponse {
        $user = $request->user();

        $lastMonth      = Carbon::now()->subMonth();
        $startLastMonth = $lastMonth->copy()->startOfMonth();
        $endLastMonth   = $lastMonth->copy()->endOfMonth();

        // ── CASH ──────────────────────────────────────────────
        $totalCashBalance = CashIncome::where('user_id', $user->id)->sum('amount')
                          - CashExpense::where('user_id', $user->id)->sum('amount');

        $cashLastMonthIncome = CashIncome::where('user_id', $user->id)
            ->whereBetween('date', [$startLastMonth, $endLastMonth])->sum('amount');

        $cashLastMonthExpense = CashExpense::where('user_id', $user->id)
            ->whereBetween('date', [$startLastMonth, $endLastMonth])->sum('amount');

        // ── BANK per akun ─────────────────────────────────────
        $bankAccounts = BankAccount::where('user_id', $user->id)->get();

        $bankAccountsSummary = $bankAccounts->map(function ($account) use ($startLastMonth, $endLastMonth) {
            $totalIncome  = BankIncome::where('bank_account_id', $account->id)->sum('amount');
            $totalExpense = BankExpense::where('bank_account_id', $account->id)->sum('amount');

            $lastMonthIncome  = BankIncome::where('bank_account_id', $account->id)
                ->whereBetween('date', [$startLastMonth, $endLastMonth])->sum('amount');
            $lastMonthExpense = BankExpense::where('bank_account_id', $account->id)
                ->whereBetween('date', [$startLastMonth, $endLastMonth])->sum('amount');

            return [
                'id'                 => $account->id,
                'bank_name'          => $account->bank_name,
                'account_name'       => $account->account_name,
                'account_number'     => $account->account_number,
                'balance'            => $totalIncome - $totalExpense,
                'last_month_income'  => $lastMonthIncome,
                'last_month_expense' => $lastMonthExpense,
            ];
        });

        // ── Pengeluaran bank per jenis bulan lalu ─────────────
        $expenseByType = BankExpense::with(['bankExpenseType', 'bankAccount'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startLastMonth, $endLastMonth])
            ->get()
            ->groupBy('bank_expense_type_id')
            ->map(function ($expenses) {
                $first = $expenses->first();
                return [
                    'type_id'   => $first->bank_expense_type_id,
                    'type_name' => $first->bankExpenseType->name,
                    'total'     => $expenses->sum('amount'),
                    'per_bank'  => $expenses->groupBy('bank_account_id')
                        ->map(function ($bankExpenses) {
                            $acc = $bankExpenses->first()->bankAccount;
                            return [
                                'bank_account_id' => $acc->id,
                                'bank_name'       => $acc->bank_name,
                                'amount'          => $bankExpenses->sum('amount'),
                            ];
                        })->values(),
                ];
            })->values();

        return response()->json([
            'period_label'  => $lastMonth->locale('id')->translatedFormat('F Y'),
            'cash' => [
                'balance'            => $totalCashBalance,
                'last_month_income'  => $cashLastMonthIncome,
                'last_month_expense' => $cashLastMonthExpense,
            ],
            'bank' => [
                'total_balance'  => $bankAccountsSummary->sum('balance'),
                'accounts'       => $bankAccountsSummary,
                'expense_by_type'=> $expenseByType,
            ],
            'net_worth' => $totalCashBalance + $bankAccountsSummary->sum('balance'),
        ]);
    }
}
