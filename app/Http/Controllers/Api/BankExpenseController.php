<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankExpenseRequest;
use App\Models\BankAccount;
use App\Models\BankExpense;
use App\Models\BankExpenseType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BankExpense::with(['bankAccount', 'bankExpenseType'])
            ->where('user_id', $request->user()->id);
        
        if($request->filled('bank_account_id')) {
            $query->where('bank_account_id', $request->bank_account_id);
        }

        $bankExpenses = $query->orderBy('date', 'desc')->paginate(15);

        return response()->json($bankExpenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankExpenseRequest $request)
    {
        $validated = $request->validate();

        // Cek 1: apakah akun bank (yang baru dipilih) milik user ini?
        $bankAccount = BankAccount::where('id', $validated['bank_account_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'message' => 'Akun bank tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        // Cek 2: apakah jenis pemasukan milik user ini?
        $expenseType = BankExpenseType::where('id', $validated['bank_income_type_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$expenseType) {
            return response()->json([
                'message' => 'Jenis pengeluaran tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }
        
        $expense = BankExpense::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'data' => $expense->load(['bankAccount', 'bankExpenseType']),
            'message' => 'Pengeluaran bank berhasil ditambahkan',
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBankExpenseRequest $request, BankExpense $bankExpense): JsonResponse
    {
        $this->authorize('update', $bankExpense);
        $validated = $request->validate();

        // Cek 1: apakah akun bank (yang baru dipilih) milik user ini?
        $bankAccount = BankAccount::where('id', $validated['bank_account_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'message' => 'Akun bank tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        // Cek 2: apakah jenis pemasukan milik user ini?
        $expenseType = BankExpenseType::where('id', $validated['bank_income_type_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$expenseType) {
            return response()->json([
                'message' => 'Jenis pengeluaran tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        $bankExpense->update($validated);

        return response()->json([
            'data' => $bankExpense->load(['bankAccount', 'bankExpenseType']),
            'message' => 'Pengeluaran bank berhasil diubah',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankExpense $bankExpense): JsonResponse
    {
        $this->authorize('delete', $bankExpense);

        $bankExpense->delete();

        return response()->json(['message' => 'Pengeluaran bank berhasil dihapus'], 200);
    }
}
