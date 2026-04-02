<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashIncomeTypeRequest;
use App\Models\CashIncomeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashIncomeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $types = CashIncomeType::where('user_id', $request->user()->id)
            ->orderBy('name')->get();
        return response()->json(['data' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CashIncomeTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $type = CashIncomeType::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);
        return response()->json([
            'data' => $type, 
            'message' => 'Jenis pemasukan cash berhasil ditambahkan',
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CashIncomeTypeRequest $request, CashIncomeType $cashIncomeType)
    {
        $this->authorize('update', $cashIncomeType);
        $validated = $request->validated();
        
        $cashIncomeType->update($validated);
        return response()->json([
            'data' => $cashIncomeType,
            'message' => 'Jenis pemasukan cash berhasil diubah',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashIncomeType $cashIncomeType): JsonResponse
    {
        $this->authorize('delete', $cashIncomeType);
        $cashIncomeType->delete();
        return response()->json([
            'message' => 'Jenis pemasukan cash berhasil dihapus',
        ])
    }
}
