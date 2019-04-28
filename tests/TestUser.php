<?php

namespace TaylorNetwork\Tests;

use Illuminate\Foundation\Auth\User;
use TaylorNetwork\Setting\Traits\HasSettings;

class TestUser extends User
{
    use HasSettings;

    protected $guarded = [];
}
