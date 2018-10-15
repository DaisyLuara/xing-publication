<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Customer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Auth\Models\User;
use App\Policies\CustomerPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Customer::class => CustomerPolicy::class,
        Company::class => CompanyPolicy::class,
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Horizon::auth(function ($request) {
            // 是否是超级管理员
            return \Auth::user()->hasRole(['super-admin']);
        });
    }
}
