<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashIncome extends Model
{
    /** @use HasFactory<\Database\Factories\CashIncomeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'cash_income_type_id',
        'amount',
        'note',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashIncomeType(): BelongsTo
    {
        return $this->belongsTo(cashIncomeType::class);
    }
}
