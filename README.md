# User Setting Class for Laravel

This provides an easy way to store user settings. It includes the `Setting` facade, `Setting` model and settings table migration.

## Install

Via Composer

``` bash
$ composer require taylornetwork/setting
```

## Setup

Laravel should auto discover this package and as such it should work out of the box. If not see Manual Setup below.

---

Optionally, you can add a `hasMany` relationship in your `App\User` model to make settings readily available by `$user->settings`

In `App\User`

``` php
namespace App;

use Illuminate\Eloquent\Model;

class User extends Model
{

    // Code

    /**
     * User's settings from TaylorNetwork\Setting package
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany('TaylorNetwork\Setting\Setting');
    }

    // Code
}
```

### Manual Setup 

Add the service provider to the providers array in your `config/app.php` file.

``` php
'providers' => [

    TaylorNetwork\Setting\SettingServiceProvider::class,

];
```

---

If you want to use the `Setting` facade, add the facade to the aliases array in your `config/app.php` file.

``` php
'aliases' => [

    'Setting' => TaylorNetwork\Setting\Facades\Setting::class,

];
```


## Migrate Database Table

``` bash
$ php artisan migrate
```

This will create the `settings` table in your database.

## Usage

You use the package either with the facade or by creating a new instance of the class.

It is probably best to use the facade, because the class extends Laravel's `Illuminate\Eloquent\Model` which will require you to instantiate the class on every call.
If not, the class may return an already found setting.

### Class

By default the class uses Laravel's `Illuminate\Support\Facades\Auth` facade to get the logged in user and all settings are saved to that user.

Include the `TaylorNetwork\Setting\Setting` class in your class and instantiate it.

``` php
use TaylorNetwork\Setting\Setting;

class DummyClass
{
    public function findSetting($key)
    {
        return (new Setting)->get($key, 'defaultValue');
    }
}
```

#### Available Methods

The setting class has a few methods to get and set settings, but extends `Illuminate\Eloquent\Model` and has that functionality as well.

##### get (string $key, mixed $defaultValue = null)

Get the user's setting by a key or return a given default value.

With default value

``` php
(new TaylorNetwork\Setting\Setting)->get('key', 'DEFAULT');
```

If logged in user has setting with key value `key`, it would return that value, otherwise it would return `'DEFAULT'`

---

Without default value

``` php
(new TaylorNetwork\Setting\Setting)->get('key');
```

If logged in user has setting with key value `key`, it would return that value, otherwise it would return `null`

##### set (string $key, mixed $value)

Set the user's setting for a key and value.

``` php
(new Setting)->set('key', 'somevalue');
```

If a logged in user exists and no key `key` exists, returns instance of the `TaylorNetwork\Setting\Setting` model.

``` php
TaylorNetwork\Setting\Setting { #000
     id: 1,
     user_id: 1,
     key: 'key',
     value: 'somevalue',
     created_at: "2016-11-14 13:06:59",
     updated_at: "2016-11-14 13:06:59",
   }
```

---

If a logged in user exists and key `key` already exists, it will be updated and return an instance of the `TaylorNetwork\Setting\Setting` model

``` php
TaylorNetwork\Setting\Setting { #000
     id: 1,
     user_id: 1,
     key: 'key',
     value: 'somevalue',
     created_at: "2016-11-14 13:06:59",
     updated_at: "2016-11-16 13:06:59",
   }
```

---

If no logged in user exists `false` is returned.


#### Additional Methods

Since `TaylorNetwork\Setting\Setting` extends `Illuminate\Eloquent\Model`, all methods available through Laravel are also available.

##### user()

By default the `TaylorNetwork\Setting\Setting` class includes a `$this->belongsTo('App\User')` making the `App\User` instance available through the `user` function.

### Facade

Include the facade `TaylorNetwork\Setting\Facades\Setting` at the top of your class.

``` php
use TaylorNetwork\Setting\Facades\Setting;

class DummyClass
{
    public function dummyMethod ()
    {
        return Setting::get('setting.key', 'defaultValue');
    }
}
```

All the methods in the class are accessible statically.


## Credits

- Main Author: [Sam Taylor][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/taylornetwork