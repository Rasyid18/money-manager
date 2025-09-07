<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BelongsToUser implements ValidationRule
{
    protected string $model;
    protected string $column;

    public function __construct(string $model, string $column = 'id')
    {
        $this->model = $model;
        $this->column = $column;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }
        $exist = ($this->model)::query()->where($this->column, $value)->where('user_id', auth()->id())->exists();
        if (!$exist) {
            $fail("The selected {$attribute} is invalid.");
        }
    }
}
