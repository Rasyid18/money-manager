<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'parent_id', 'user_id', 'starting_balance'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
