<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankIncomeTypeRequest;
use App\Models\BankIncome;
use App\Models\BankIncomeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankIncomeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $types = BankIncomeType::where('user_id', $request->user()->id)
            ->orderBy('name')->get();
        return response()->json(['data' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankIncomeTypeRequest $request)
    {
        $validated = $request->validated();
        $type = BankIncome::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);
        
        return response()->json([
            'data' => $type,
            'message' => 'Jenis pemasukan bank berhasil ditambah'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankIncomeTypeRequest $request, BankIncomeType $bankIncomeType)
    {
        $this->authorize('update', $bankIncomeType);
        $bankIncomeType->update($request->validated());
        return response()->json([
            'data' => $bankIncomeType,
            'message' => 'Jenis pemasukan bank berhasil ditambah',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankIncomeType $bankIncomeType)
    {
        $this->authorize('delete', $bankIncomeType);
        $bankIncomeType->delete();
        return response()->json([
            'message' => 'Jenis pemasukan bank berhasil dihapus'
        ]);
    }
}
