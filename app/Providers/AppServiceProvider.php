<?php

namespace App\Providers;

use App\Models\BankExpenseType;
use App\Models\BankIncome;
use App\Models\BankIncomeType;
use App\Models\CashExpense;
use App\Models\CashExpenseType;
use App\Models\CashIncome;
use App\Models\CashIncomeType;
use App\Policies\BankExpensePolicy;
use App\Policies\BankExpenseTypePolicy;
use App\Policies\BankIncomePolicy;
use App\Policies\BankIncomeTypePolicy;
use App\Policies\CashExpensePolicy;
use App\Policies\CashExpenseTypePolicy;
use App\Policies\CashIncomePolicy;
use App\Policies\CashIncomeTypePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(CashIncomeType::class, CashIncomeTypePolicy::class);
        Gate::policy(CashIncome::class, CashIncomePolicy::class);
        Gate::policy(CashExpenseType::class, CashExpenseTypePolicy::class);
        Gate::policy(CashExpense::class, CashExpensePolicy::class);
        Gate::policy(BankIncomeType::class, BankIncomeTypePolicy::class);
        Gate::policy(BankIncome::class, BankIncomePolicy::class);
        Gate::policy(BankExpenseType::class, BankExpenseTypePolicy::class);
        Gate::policy(BankExpense::class, BankExpensePolicy::class);
    }
}
