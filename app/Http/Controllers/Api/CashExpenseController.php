<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashExpenseRequest;
use App\Models\CashExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $expenses = CashExpense::with('cashExpenseType')
            ->where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->paginate(15);
        return response()->json($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CashExpenseRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $expense = CashExpense::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);
        return response()->json([
            'data' => $expense->load('cashExpenseType'),
            'message' => 'Pengeluaran cash berhasil ditambahkan',
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CashExpenseRequest $request, CashExpense $cashExpense)
    {
        $this->authorize('update', $cashExpense);
        $validated = $request->validated();

        $request->user()->cashExpenseTypes()->findOrFail($validated['cash_expense_type_id']);
        
        $cashExpense->update($validated);

        return response()->json([
            'data' => $cashExpense->load('cashIncomeType'),
            'message' => 'Pengeluaran cash berhasil diubah',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashExpense $cashExpense)
    {
        $this->authorize('delete', $cashExpense);
        $cashExpense->delete();
        return response()->json(['message' => 'Pengeluaran cash berhasil dihapus']);
    }
}
