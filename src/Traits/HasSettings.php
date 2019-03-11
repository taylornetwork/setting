<?php 

use TaylorNetwork\Setting\Setting;
use Exception;

namespace TaylorNetwork\Setting\Traits;

trait HasSettings
{
	public function settings()
	{
		return $this->hasMany(Setting::class);
	}

	public function setting($key, $default = null)
	{
		return $this->settings()->where('key', $key)->first()->value ?? $default;
	}

	public function updateSetting($key, $value)
	{
		try {
			if($setting = $this->settings()->where('key', $key)->first()) {
				$setting->update([ 'value' => $value ]); 
			} else {
				$this->settings()->create([ 'key' => $key, 'value' => $value ]);
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}