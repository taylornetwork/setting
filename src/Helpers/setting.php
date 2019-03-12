<?php

use TaylorNetwork\Setting\Facades\Setting;

if(!function_exists('setting')) {

    /**
     * Get a user setting
     *
     * @param string $key
     * @param mixed $default
     * @param mixed $guard
     * @return mixed
     */
    function setting($key, $default = null, $guard = null)
    {
        if(is_array($guard)) {
            foreach($guard as $g) {
                $setting = Setting::guard($g)->get($key, $default);
                if($setting !== $default) {
                    return $setting;
                }
            }
            return $default;
        }

        if($guard !== null) {
            return Setting::guard($guard)->get($key, $default);
        }

        return Setting::get($key, $default);
    }

}