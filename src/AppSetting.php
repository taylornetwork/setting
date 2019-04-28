<?php

namespace TaylorNetwork\Setting;

use Illuminate\Database\Eloquent\Model;
use TaylorNetwork\Setting\Traits\HasValueAttributes;

class AppSetting extends Model
{
    use HasValueAttributes;

    protected $fillable = ['key', 'value'];

    protected $primaryKey = 'key';

    /**
     * Search for an app setting.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function search($key)
    {
        return self::where('key', $key)->first();
    }

    /**
     * Get a setting, or return default.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->search($key)->value ?? $default;
    }

    /**
     * Set a setting.
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
    public function set($key, $value)
    {
        $setting = $this->search($key);

        if ($setting) {
            $setting->update(['value' => $value]);

            return $setting;
        }

        return self::create([
            'key'   => $key,
            'value' => $value,
        ]);
    }
}
