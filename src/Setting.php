<?php


namespace TaylorNetwork\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    protected $fillable = [ 'user_id', 'key', 'value' ];

    /**
     * Get the related column name
     *
     * @return string
     */
    private function getRelatedColumn()
    {
        return config('setting.related_column', 'user_id');
    }

    /**
     * Check if a user is logged in
     *
     * @return bool
     */
    private function isGuest()
    {
        return Auth::guest();
    }

    /**
     * Get current user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function getCurrentUser()
    {
        return Auth::user();
    }

    /**
     * Belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('setting.model', '\\App\\User'));
    }

    /**
     * Search for a user's setting
     *
     * @param string $key
     * @return mixed
     */
    private function search($key)
    {
        if(!$this->isGuest())
        {
            return self::where('user_id', $this->getCurrentUser()->getAuthIdentifier())->where('key', $key)->first();
        }
        return null;
    }

    /**
     * Get a key of a user, or return default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $setting = $this->search($key);

        if($setting)
        {
            return $setting->value;
        }

        return $default;
    }

    /**
     * Set a setting
     *
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function set($key, $value)
    {
        if(!$this->isGuest())
        {
            $setting = $this->search($key);

            if($setting)
            {
                $setting->update([ 'value' => $value ]);
                return $setting;
            }

            return self::create([
                $this->getRelatedColumn() => $this->getCurrentUser()->getAuthIdentifier(),
                'key' => $key,
                'value' => $value
            ]);
        }
        return false;
    }
}
