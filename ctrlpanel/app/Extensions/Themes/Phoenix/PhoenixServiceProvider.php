<?php

namespace App\Extensions\Themes\Phoenix;

use App\Settings\GeneralSettings;
use Illuminate\Support\ServiceProvider;

class PhoenixServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(GeneralSettings $generalSettings): void
    {
        $generalSettings = $this->app->make(GeneralSettings::class);

        if ($generalSettings->theme !== 'phoenix') {
            return;
        }

        /**
         * GLOBAL CREDITS DISPLAY NAME
         */
        view()->share('credits_display_name', $generalSettings->credits_display_name);

        // $this->app->bind('App\Http\Controllers\HomeController', 'App\Extensions\Themes\Phoenix\Controllers\HomeController');
    }
}
