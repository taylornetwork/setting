<?php

namespace TaylorNetwork\Setting;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

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

        $this->publishes([
            __DIR__.'/config/setting.php' => config_path('setting.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/setting.php', 'setting');

        App::bind('Setting', function(){
            return new Setting;
        });
    }
}
