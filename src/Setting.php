<?php


namespace TaylorNetwork\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [ 'user_id', 'key', 'value' ];

    /**
     * Belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Search for a user's setting
     *
     * @param string $key
     * @return mixed
     */
    private function search($key)
    {
        if(!Auth::guest())
        {
            return self::where('user_id', Auth::user()->id)->where('key', $key)->first();
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
        if(!Auth::guest())
        {
            $setting = $this->search($key);

            if($setting)
            {
                return $setting->update([ 'value' => $value ]);
            }

            return self::create([ 'user_id' => Auth::user()->id, 'key' => $key, 'value' => $value ]);
        }
        return false;
    }
}