<?php

namespace App\Providers;

use App\Rules\PasswordRule;
use Illuminate\Support\Facades\Validator;
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
        Validator::extend('big_password', function ($attribute, $value, $parameters, $validator) {
            $rule = new PasswordRule();
            return $rule->passes($attribute, $value);
        });
    }
}
