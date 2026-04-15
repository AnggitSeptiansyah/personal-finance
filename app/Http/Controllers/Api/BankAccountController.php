<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountRequest;
use App\Models\BankAccount;
use App\Models\BankExpense;
use App\Models\BankIncome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $accounts = BankAccount::where('user_id', $request->user()->id)
            ->orderBy('bank_name')
            ->get()
            ->map(function ($account) {
                $balance = BankIncome::where('bank_account_id', $account->id)->sum('amount')
                    - BankExpense::where('bank_account_id', $account->id)->sum('amount');
                return [
                    'id' => $account->id,
                    'bank_name' => $account->bank_name,
                    'account_number' => $account->account_number,
                    'account_name' => $account->account_name,
                    'description' => $account->description,
                    'balance' => $balance,
                ];
            });

            return response()->json(['data' => $accounts]);
    }

    public function store(BankAccountRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $account = BankAccount::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'data' => $account,
            'message' => 'Akun bank berhasil ditambahkan',
        ], 201);
    }

    public function update(BankAccountRequest $request, BankAccount $bankAccount): JsonResponse
    {
        $this->authorize('update', $bankAccount);

        $bankAccount->validate($request->validated());

        return response()->json([
            'data' => $bankAccount,
            'message' => 'Akun bank telah berhasil diubah'
        ]);
    }

    public function destroy(BankAccount $bankAccount): JsonResponse
    {
        $this->authorize('delete', $bankAccount);
        $bankAccount->delete();

        return response()->json([
            'message' => 'Akun bank berhasil dihapus'
        ]);
        
    }
}
