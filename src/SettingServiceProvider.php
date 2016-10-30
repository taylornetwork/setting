<?php

namespace TaylorNetwork\Setting;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use TaylorNetwork\Setting\Setting;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('Setting', function(){
            return new Setting;
        });
    }
}
