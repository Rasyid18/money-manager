<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    public function findByID(int $id): ?Transaction;
    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection;
    public function create(array $param): Transaction;
    public function update(Transaction $transaction, array $param): Transaction;
    public function remove(Transaction $transaction);
    public function findTrashedByID(int $id): ?Transaction;
    public function restore(Transaction $transaction);
    public function forceDelete(Transaction $transaction);

    public function getItemsName(array $search = []): array;
    public function getPlacesName(array $search = []): array;
}
