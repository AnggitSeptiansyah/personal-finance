<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankExpenseTypeRequest;
use App\Models\BankExpenseType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $bankExpenseTypes = BankExpenseType::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();
        
        return response()->json(['data' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankExpenseTypeRequest $request): JsonResponse
    {
        $validated = $request->validate();

        $bankExpenseType = BankExpenseType::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'data' => $bankExpenseType,
            'message' => 'Jenis pengeluaran bank berhasil ditambahkan',
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBankExpenseTypeRequest $request, BankExpenseType $bankExpenseType)
    {
        $this->authorize('update', $bankExpenseType);

        $bankExpenseType->update($request->validated());

        return response()->json([
            'data' => $bankExpenseType,
            'message' => 'Jenis pengeluaran bank berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankExpenseType $bankExpenseType): JsonResponse
    {
        $this->authorize('delete', $bankExpenseType);
        $bankExpenseType->delete();
        
        return response()->json([
            'message' => 'Jenis pengeluaran bank berhasil dihapus'
        ])
    }
}
