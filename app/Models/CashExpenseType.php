<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use function PHPSTORM_META\map;

class CashExpenseType extends Model
{
    /** @use HasFactory<\Database\Factories\CashExpenseTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashExpense(): HasMany
    {
        return $this->hasMany(CashExpense::class);
    }
}
