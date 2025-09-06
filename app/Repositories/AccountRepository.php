<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AccountRepository implements AccountRepositoryInterface
{
    protected Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function findByID(int $id): ?Account
    {
        return $this->account->find($id);
    }

    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection
    {
        return $this->account->with(['parentAccount', 'children'])->where($search)->where(function ($query) use ($searchOr) {
            foreach ($searchOr as $condition) {
                [$column, $operator, $value] = $condition;
                $query->whereOr($column, $operator, $value);
            }
        })->whereNotIn('id', $exclude)->whereNull('parent_id')->get();
    }

    public function create(array $param): Account
    {
        return $this->account->create($param);
    }

    public function update(Account $account, array $param): Account
    {
        $account->fill($param);
        $account->save();

        return $account;
    }

    public function remove(Account $account)
    {
        $account->delete();
    }

    public function findTrashedByID(int $id): ?Account
    {
        return $this->account->withTrashed()->where("id", $id)->first();
    }

    public function restore(Account $account)
    {
        $account->restore();
    }

    public function forceDelete(Account $account)
    {
        $account->forceDelete();
    }
}
