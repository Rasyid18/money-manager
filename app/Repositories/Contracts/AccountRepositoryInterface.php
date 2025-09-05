<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

interface AccountRepositoryInterface
{
    public function findByID(int $id): ?Account;
    public function get(array $search = [], array $searchOr = [], array $exclude = []): Collection;
    public function create(array $param): Account;
    public function update(Account $account, array $param): Account;
    public function remove(Account $account);
    public function findTrashedByID(int $id): ?Account;
    public function restore(Account $account);
    public function forceDelete(Account $account);
}
