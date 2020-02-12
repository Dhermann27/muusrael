<?php

namespace App\Providers;

use App\Enums\Chargetypename;
use App\Enums\Usertype;
use App\ThisyearCharge;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('has-paid', function () {
            $paid = 1;
            $scholar = 0;
            if (isset(Auth::user()->camper) && isset(Auth::user()->camper->family_id)) {
                $paid = ThisyearCharge::where('family_id', Auth::user()->camper->family_id)
                    ->where(function ($query) {
                        $query->where('chargetype_id', Chargetypename::Deposit)->orWhere('amount', '<', 0);
                    })->get()->sum('amount');
                $scholar = Auth::user()->camper->family->is_scholar;
            }
            return $paid <= 0 || $scholar;
        });

        Gate::define('is-council', function ($user) {
            return $user->usertype > Usertype::Camper;
        });

        Gate::define('is-super', function ($user) {
            return $user->usertype > Usertype::Pc;
        });

        Gate::define('readonly', function($user) {
           return session()->has('camper_id') && $user->usertype == Usertype::Pc;
        });
    }


}
