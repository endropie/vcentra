<?php

namespace App\Providers;

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
        $this->extendValidator();
    }

    protected function extendValidator()
    {
        Validator::extend('phone', function ($attribute, $value, $parameters) {
            $value = trim($value);
            if ($value == '') return true;
            $match = '/^([0-9\s\-\+\(\)]*)$/';
            if (preg_match($match, $value)) return true;
            else {
                return false;
            }
        }, 'The :attribute is invalid phone number');
    }
}
