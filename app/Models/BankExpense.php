<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankExpense extends Model
{
    /** @use HasFactory<\Database\Factories\BankExpenseFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'bank_expense_type_id', 'amount', 'note', 'date'];
    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function bankExpenseType(): BelongsTo {
        return $this->belongsTo(BankExpenseType::class);
    }
}
