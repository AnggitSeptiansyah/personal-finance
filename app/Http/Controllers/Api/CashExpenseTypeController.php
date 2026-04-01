<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashExpenseTypeRequest;
use App\Models\CashExpenseType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $types = CashExpenseType::where('user_id', $request->user()->id)
            ->orderBy('name')->get();
        return response()->json(['data' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validated();
        $type = CashExpenseType::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);
        return response()->json([
            'data' => $type,
            'message' => 'Jenis pengeluaran cash berhasil ditambah', 201
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CashExpenseTypeRequest $request, CashExpenseType $cashExpenseType): JsonResponse
    {
        $this->authorize('update', $cashExpenseType);
        $cashExpenseType->update($request->validated());

        return response()->json([
            'data' => $cashExpenseType,
            'message' => 'Jenis pengeluaran cash behasil diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashExpenseType $cashExpenseType)
    {
        $this->authorize('delete', $cashExpenseType);
        $cashExpenseType->delete();
        return response()->json([
            'message' => 'Jenis pengeluaran cash berhasil dihapus'
        ]);
    }
}
