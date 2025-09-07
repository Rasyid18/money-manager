<?php

namespace App\Repositories;

use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionCategoryRepository implements TransactionCategoryRepositoryInterface
{
    protected TransactionCategory $category;

    public function __construct(TransactionCategory $category)
    {
        $this->category = $category;
    }

    public function findByID(int $id): ?TransactionCategory
    {
        return $this->category->find($id);
    }

    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection
    {
        return $this->category->with(['parentCategory', 'children'])->where($search)->where(function ($query) use ($searchOr) {
            foreach ($searchOr as $condition) {
                [$column, $operator, $value] = $condition;
                $query->whereOr($column, $operator, $value);
            }
        })->whereNotIn('id', $exclude)->whereNull('parent_id')->get();
    }

    public function create(array $param): TransactionCategory
    {
        return $this->category->create($param);
    }

    public function update(TransactionCategory $category, array $param): TransactionCategory
    {
        $category->fill($param);
        $category->save();

        return $category;
    }

    public function remove(TransactionCategory $category)
    {
        $category->delete();
    }

    public function findTrashedByID(int $id): ?TransactionCategory
    {
        return $this->category->withTrashed()->where('id', $id)->first();
    }

    public function restore(TransactionCategory $category)
    {
        $category->restore();
    }

    public function forceDelete(TransactionCategory $category)
    {
        $category->forceDelete();
    }

    public function getCurrentSortOrder(int $userId): int
    {
        $last = $this->category->where('user_id', $userId)->orderBy('sort_order', 'desc')->first();
        return ($last ? $last->sort_order : 0) + 1;
    }
}
