<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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

        $messages = [
            'credit_card' => [
                'card_invalid' => '',
                'card_pattern_invalid' => '',
                'card_length_invalid' => '',
                'card_checksum_invalid' => '',
                'card_expiration_year_invalid' => '',
                'card_expiration_month_invalid' => '',
                'card_expiration_date_invalid' => '',
                'card_expiration_date_format_invalid' => '',
                'card_cvc_invalid' => ''
            ]
        ];


        // foreach ($messages as $key => $value) {
        //     Validator::replacer($key, function ($message, $attribute, $rule, $parameters) use ($value) {
        //         return $value[$rule];
        //     });
        // }

        Validator::replacer('credit_card.card_invalid', function ($message, $attribute, $rule, $parameters) {
            return "Test";
        });
    }
}
