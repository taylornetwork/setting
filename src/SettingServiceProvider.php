<?php

namespace TaylorNetwork\Setting;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

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
            __DIR__.'/migrations/' => database_path('migrations'),
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

        App::bind('UserSetting', function () {
            $settingModel = config('setting.user_setting_model', UserSetting::class);

            return new $settingModel();
        });

        App::bind('AppSetting', function () {
            $settingModel = config('setting.app_setting_model', AppSetting::class);

            return new $settingModel();
        });

        foreach (glob(__DIR__.'/Helpers/*.php') as $helper) {
            require_once $helper;
        }
    }
}
