# Settings for Laravel User and App

This package provides an easy way to access settings for the current logged in user as well as global app settings.

**NOTE: Some features have changed in v2**

1. [Changes](#changes)
2. [About](#about)
3. [Install](#install)
4. [Usage](#usage)
	- [Available Methods](#available-methods)
	- [Classes](#classes)
	- [Facades](#facades)
	- [Helper Functions](#helpers)
5. [HasSettings Trait](#hassettings-trait)
6. [Config](#config)
7. [Alternate Configuration](#alternate-configuration)
8. [Extending](#extending)
9. [License](#license)

## Changes

**v2.1**

- Added support for float type values.


**v2.0**

Support for app specific AND user specific settings has been added. 

- Added `AppSetting` model and facade
- Added `app_setting` helper
- Added `user_setting` helper
- Renamed `Setting` model and facade to `UserSetting`
- Deprecated `setting` helper
- Renamed config key `setting_model` to `user_setting_model`

If you use the `setting` helper it will still work but will be removed, so use `user_setting` instead. The `Setting` facade has been renamed to `UserSetting` if you still have code with the `Setting` facade you can rename the alias in your `config/app.php`

```php
'aliases' => [
	...
	'Setting' => TaylorNetwork\Setting\Facades\UserSetting::class,
	...
],
```

## About

This package uses Laravel's `auth` function to access the logged in user and return their setting for a given key, or a default value. Similar to the way that the `config` and `env` functions work. 

When using the `HasSettings` trait it allows you to access a non-logged in user's settings.

By default if the default value is not set and a setting cannot be found, `null` is returned. The default value will also be returned if there is no logged in user.

It also includes support for app specific settings via `AppSetting` 

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

*Note: the `UserSetting` and `AppSetting` classes extend `Illuminate\Database\Eloquent\Model`*

### Available Methods

All of these methods are available when using the class or facade.

**guard($guard) [UserSetting only]**

This will allow you to manually set an auth guard before trying to find an active user.

```php
UserSetting::guard('api')->get('key', 'defaultValue');
```

**get($key, $default = null) [UserSetting and AppSetting]**

Get a setting for the app/logged in user or return the default value.

```php
UserSetting::get('key');

AppSetting::get('key');
```

**set($key, $value) [UserSetting and AppSetting]**

Creates or updates the setting for the logged in user or app.

```php
UserSetting::set('someKey', 'someValue');

AppSetting::set('someKey', 'someValue');
```

### Classes

UserSetting:

```php
use TaylorNetwork\Setting\UserSetting;

$setting = new UserSetting;
$setting->get('someKey', 'defaultValue');

```

AppSetting:

```php
use TaylorNetwork\Setting\AppSetting;

$setting = new AppSetting;
$setting->get('someKey', 'defaultValue');

```

### Facades

UserSetting: 

```php

UserSetting::get('someKey');

UserSetting::set('someKey', 'someValue');

UserSetting::guard('api')->get('someKey', 'Default Value!');

```

AppSetting:

```php

AppSetting::get('someKey');

AppSetting::set('someKey', 'someValue');

```

### Helpers

The `user_setting` and `app_setting` helper functions are aliases for the `get` method. The `user_setting` helper has an optional third parameter to pass a guard or guards.

Example: 

```php
// Returns the user's value or null
user_setting('key');

// Returns the app's value or null
app_setting('key');

// Returns the user's value or 'defaultValue'
user_setting('key', 'defaultValue');

// Returns the app's value or 'defaultValue'
app_setting('key', 'defaultValue');

// Returns the user's value or 'defaultValue' using the 'api' guard
user_setting('key', 'defaultValue', 'api');

// Will try and get a value from each guard in order
// If a value other than the default value is found it will immediately return it.
// If none is found after using all the guards in the array, the default value is returned.
user_setting('key', 'defaultValue', ['web', 'api']);
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

You can extend the `UserSetting` and `AppSetting` models if you need additional functionality. 

```php
namespace App;

use TaylorNetwork\Setting\UserSetting;

class Setting extends UserSetting
{
	// --
}
```

Be sure to update the config with the appropriate new `user_setting_model` or `app_setting_model`.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
