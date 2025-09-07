<?php

namespace App\Repositories\Contracts;

use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Collection;

interface TransactionCategoryRepositoryInterface
{
    public function findByID(int $id): ?TransactionCategory;
    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection;
    public function create(array $param): TransactionCategory;
    public function update(TransactionCategory $category, array $param): TransactionCategory;
    public function remove(TransactionCategory $category);
    public function findTrashedByID(int $id): ?TransactionCategory;
    public function restore(TransactionCategory $category);
    public function forceDelete(TransactionCategory $category);

    public function getCurrentSortOrder(int $userId): int;
}
