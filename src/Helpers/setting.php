<?php

if (!function_exists('setting') && in_array('setting', config('setting.register_helpers', ['setting']))) {

    /**
     * Get a user setting.
     *
     * @param string $key
     * @param mixed  $default
     * @param mixed  $guard
     *
     * @return mixed
     *
     * @deprecated use user_setting()
     */
    function setting($key, $default = null, $guard = null)
    {
        return user_setting($key, $default, $guard);
    }
}
