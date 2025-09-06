<?php

namespace App\Repositories\Contracts;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Collection;

interface BudgetRepositoryInterface
{
    public function findByID(int $id): ?Budget;
    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection;
    public function create(array $param): Budget;
    public function update(Budget $budget, array $param): Budget;
    public function remove(Budget $budget);
    public function findTrashedByID(int $id): ?Budget;
    public function restore(Budget $budget);
    public function forceDelete(Budget $budget);
}
