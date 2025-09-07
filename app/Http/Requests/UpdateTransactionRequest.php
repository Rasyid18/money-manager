<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Rules\BelongsToUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', new BelongsToUser(TransactionCategory::class)],
            'type'     => ['nullable', Rule::in(Transaction::TYPES)],
            'from'     => ['nullable', new BelongsToUser(Account::class)],
            'to'       => ['nullable', new BelongsToUser(Account::class)],
            'budget'   => ['nullable', new BelongsToUser(Budget::class)],
            'date'     => ['nullable', 'date_format:Y-m-d H:i:s'],
            'amount'   => ['nullable', 'numeric'],
            'items'    => ['nullable', 'string'],
            'place'    => ['nullable', 'string'],
            'notes'    => ['nullable', 'string'],
        ];
    }
}
