<?php

use TaylorNetwork\Setting\Facades\AppSetting;

if(!function_exists('app_setting')) {

    /**
     * Get an app setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function app_setting($key, $default = null)
    {
        return AppSetting::get($key, $default);
    }

}