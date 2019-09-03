<?php

use TaylorNetwork\Setting\Facades\UserSetting;

if (!function_exists('user_setting') && in_array('user_setting', config('setting.register_helpers', ['user_setting']))) {

    /**
     * Get a user setting.
     *
     * @param string $key
     * @param mixed  $default
     * @param mixed  $guard
     *
     * @return mixed
     */
    function user_setting($key, $default = null, $guard = null)
    {
        if (is_array($guard)) {
            foreach ($guard as $g) {
                $setting = UserSetting::guard($g)->get($key, $default);
                if ($setting !== $default) {
                    return $setting;
                }
            }

            return $default;
        }

        if ($guard !== null) {
            return UserSetting::guard($guard)->get($key, $default);
        }

        return UserSetting::get($key, $default);
    }
}
