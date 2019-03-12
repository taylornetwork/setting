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
        ], 'config');

        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'migrations');
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
            $settingModel = config('setting.setting_model', Setting::class);
            return new $settingModel;
        });

        foreach(glob(__DIR__.'/Helpers/*.php') as $helper) {
            require_once $helper;
        }
    }
}
