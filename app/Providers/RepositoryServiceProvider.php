<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\BudgetRepository;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(BudgetRepositoryInterface::class, BudgetRepository::class);
    }

    public function boot() {}
}
