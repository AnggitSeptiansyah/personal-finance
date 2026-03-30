<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashIncomeRequest;
use App\Models\CashIncome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $incomes = CashIncome::with('cashIncomeType')
            ->where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return response()->json($incomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CashIncomeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $income = CashIncome::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'data' => $income->load('cashIncomeType'),
            'message' => 'Pemasukan cash berhasil ditambah', 201
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CashIncomeRequest $request, CashIncome $cashIncome): JsonResponse
    {
        $this->authorize('update', $cashIncome);
        $validated = $request->validated();
        $cashIncome->update($validated);
        return response()->json([
            'data' => $cashIncome->load('cashIncomeType'),
            'message' => 'Pemasukan cash berhasil diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashIncome $cashIncome): JsonResponse
    {
        $this->authorize('delete', $cashIncome);
        $cashIncome->delete();
        return response()->json([
            'message' => 'Pemasukan cash berhasil dihapus'
        ]);
    }
}
