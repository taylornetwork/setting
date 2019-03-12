# Settings for Laravel User

This package provides an easy way to access settings for the current logged in user.

1. [About](#about)
2. [Install](#install)
3. [Usage](#usage)
	- [Available Methods](#available-methods)
	- [Setting Class](#setting-class)
	- [Facade](#facade)
	- [Helper Function](#helper)
4. [HasSettings Trait](#hassettings-trait)
5. [Config](#config)
6. [Alternate Configuration](#alternate-configuration)
7. [Extending](#extending)
8. [License](#license)

## About

This package uses Laravel's `auth` function to access the logged in user and return their setting for a given key, or a default value. Similar to the way that the `config` and `env` functions work. 

When using the `HasSettings` trait it allows you to access a non-logged in user's settings.

By default if the default value is not set and a setting cannot be found, `null` is returned. The default value will also be returned if there is no logged in user.

## Install

Via Composer

```bash
$ composer require taylornetwork/setting
```

Run database migrations 

```bash
$ php artisan migrate
```

---

You can optionally add the `HasSettings` trait to your user model to access settings directly.

```php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use TaylorNetwork\Setting\Traits\HasSettings;

class User extends Authenticatable
{
   use HasSettings;
   
   // ...
}
```

## Usage

### Available Methods

All of these methods are available when using the class of facade.

*Note: the `Setting` class extends `Illuminate\Database\Eloquent\Model`*

**guard($guard)**

This will allow you to manually set an auth guard before trying to find an active user.

```php
Setting::guard('api')->get('key', 'defaultValue');
```

**get($key, $default = null)**

Get a setting for the logged in user or return the default value.

```php
Setting::get('key');
```

**set($key, $value)**

Creates or updates the setting for the logged in user.

```php
Setting::set('someKey', 'someValue');
```

Returns the setting if successful, or `false` if there is no logged in user.


### Setting Class

Example:

```php
use TaylorNetwork\Setting\Setting;

$setting = new Setting;
$setting->get('someKey', 'defaultValue');

```

### Facade

Example: 

```php

Setting::get('someKey');

Setting::set('someKey', 'someValue');

Setting::guard('api')->get('someKey', 'Default Value!');

```

### Helper

The `setting` helper function is an alias for the `get` method. It has an optional third parameter to pass a guard or guards.

Example: 

```php
// Returns the user's value or null
setting('key');

// Returns the user's value or 'defaultValue'
setting('key', 'defaultValue');

// Returns the user's value or 'defaultValue' using the 'api' guard
setting('key', 'defaultValue', 'api');

// Will try and get a value from each guard in order
// If a value other than the default value is found it will immediately return it.
// If none is found after using all the guards in the array, the default value is returned.
setting('key', 'defaultValue', ['web', 'api']);
```

## HasSettings Trait

The `HasSettings` trait adds a `settings` relation and the following methods to your model

**setting($key, $default = null)**

This is the same as the `get` method above **BUT** can be used for any user not just one that is logged in.

**updateSetting($key, $value)**

This will create or update a setting for the specified user.

## Config

If you need to change the default auth guard or user model, publish config.

```bash
$ php artisan vendor:publish --provider="TaylorNetwork\Setting\SettingServiceProvider" --tag=config
```

## Alternate Configuration

You can modify the `settings` table to suit your needs, publish the migrations

```bash
$ php artisan vendor:publish --provider="TaylorNetwork\Setting\SettingServiceProvider" --tag=migrations
```

Be sure to update the config with appropriate `related_column` if you change it. 

## Extending

You can extend the `Setting` model if you need additional functionality. 

```php
namespace App;

use TaylorNetwork\Setting\Setting as BaseSetting;

class Setting extends BaseSetting
{
	// --
}
```

Be sure to update the config with the appropriate new `setting_model`.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
