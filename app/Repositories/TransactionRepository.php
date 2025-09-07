<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function findByID(int $id): ?Transaction
    {
        return $this->transaction->find($id);
    }

    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection
    {
        return $this->transaction->with(['user', 'transactionCategory', 'fromAccount', 'toAccount', 'budget'])->where($search)->where(function ($query) use ($searchOr) {
            foreach ($searchOr as $condition) {
                [$column, $operator, $value] = $condition;
                $query->whereOr($column, $operator, $value);
            }
        })->whereNotIn('id', $exclude)->get();
    }

    public function create(array $param): Transaction
    {
        return $this->transaction->create($param);
    }

    public function update(Transaction $transaction, array $param): Transaction
    {
        $transaction->fill($param);
        $transaction->save();
        return $transaction;
    }

    public function remove(Transaction $transaction)
    {
        $transaction->delete();
    }

    public function findTrashedByID(int $id): ?Transaction
    {
        return $this->transaction->withTrashed()->where("id", $id)->first();
    }

    public function restore(Transaction $transaction)
    {
        $transaction->restore();
    }

    public function forceDelete(Transaction $transaction)
    {
        $transaction->forceDelete();
    }

    public function getItemsName(array $search = []): array
    {
        return $this->transaction->where($search)->pluck('items')->toArray();
    }

    public function getPlacesName(array $search = []): array
    {
        return $this->transaction->where($search)->pluck('place')->toArray();
    }
}
