<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    const TYPES = ['income', 'expense', 'transfer'];

    protected $fillable = ['user_id', 'transaction_category_id', 'type', 'from_account_id', 'to_account_id', 'budget_id', 'date', 'amount', 'items', 'place', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionCategory(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'form_account_id', 'id');
    }
    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
