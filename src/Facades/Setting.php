<?php

namespace TaylorNetwork\Setting\Facades;

use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{
    /**
     * Get the facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    { 
    	return "Setting";
    }
}
