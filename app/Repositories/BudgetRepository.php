<?php

namespace App\Repositories;

use App\Models\Budget;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BudgetRepository implements BudgetRepositoryInterface
{
    protected $budget;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    public function findByID(int $id): ?Budget
    {
        return $this->budget->find($id);
    }

    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection
    {
        return $this->budget->with('user')->where($search)->where(function ($query) use ($searchOr) {
            foreach ($searchOr as $condition) {
                [$column, $operator, $value] = $condition;
                $query->whereOr($column, $operator, $value);
            }
        })->whereNotIn('id', $exclude)->get();
    }

    public function create(array $param): Budget
    {
        return $this->budget->create($param);
    }

    public function update(Budget $budget, array $param): Budget
    {
        $budget->fill($param);
        $budget->save();

        return $budget;
    }

    public function remove(Budget $budget)
    {
        $budget->delete();
    }

    public function findTrashedByID(int $id): ?Budget
    {
        return $this->budget->withTrashed()->where("id", $id)->first();
    }

    public function restore(Budget $budget)
    {
        $budget->restore();
    }

    public function forceDelete(Budget $budget)
    {
        $budget->forceDelete();
    }
}
