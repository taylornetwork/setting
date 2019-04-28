<?php

namespace TaylorNetwork\Setting\Facades;

use Illuminate\Support\Facades\Facade;

class UserSetting extends Facade
{
    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'UserSetting';
    }
}
