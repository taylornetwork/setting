<?php 

namespace TaylorNetwork\Setting\Traits;

use TaylorNetwork\Setting\Setting;
use Exception;

trait HasSettings
{
	public function settings()
	{
		return $this->hasMany(Setting::class, config('setting.related_column', 'user_id'));
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
