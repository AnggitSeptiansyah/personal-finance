<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function PHPSTORM_META\map;

class BankIncome extends Model
{
    /** @use HasFactory<\Database\Factories\BankIncomeFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'bank_income_type_id',
        'amount',
        'note',
        'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankIncomeType(): BelongsTo
    {
        return $this->belongsTo(BankIncomeType::class);
    }
}
