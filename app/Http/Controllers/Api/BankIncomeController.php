<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankIncomeTypeRequest;
use App\Http\Requests\StoreBankIncomeRequest;
use App\Models\BankAccount;
use App\Models\BankIncome;
use App\Models\BankIncomeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BankIncome::with(['bankAccount', 'bankIncomeType'])
            ->where('user_id', $request->user()->id);
        
        if ($request->filled('bank_account_id')) {
            $query->where('bank_account_id', $request->bank_account_id);
        }

        $incomes = $query->orderBy('date', 'desc')->paginate(15);

        return response()->json($incomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankIncomeTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        // Cek 1: apakah akun bank milik user ini?
        $bankAccount = BankAccount::where('id', $validated['bank_account_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'message' => 'Akun bank tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        // Cek 2: apakah jenis pemasukan milik user ini?
        $incomeType = BankIncomeType::where('id', $validated['bank_income_type_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$incomeType) {
            return response()->json([
                'message' => 'Jenis pemasukan tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        $income = BankIncome::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'data' => $income->load(['bankAccount', 'bankIncomeType']),
            'message' => 'Pemasukan bank berhasil ditambahkan'
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBankIncomeRequest $request, BankIncome $bankIncome)
    {
        $this->authorize('update', $bankIncome);

        $validated = $request->validated();

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
        $incomeType = BankIncomeType::where('id', $validated['bank_income_type_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$incomeType) {
            return response()->json([
                'message' => 'Jenis pemasukan tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        $bankIncome->update($validated);
        return response()->json([
            'data' => $bankIncome->load(['bankIncomeType', 'bankAccount']),
            'message' => 'Pemasukan cash berhasil diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankIncome $bankIncome)
    {
        $this->authorize('delete', $bankIncome);
        $bankIncome->delete();

        return response()->json([
            'message' => 'Pemasukan bank berhasil dihapus'
        ]);
    }
}
