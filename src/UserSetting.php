<?php


namespace TaylorNetwork\Setting;

use Illuminate\Database\Eloquent\Model;
use TaylorNetwork\Setting\Traits\HasValueAttributes;

class UserSetting extends Model
{
    use HasValueAttributes;

    protected $fillable = [ 'user_id', 'key', 'value' ];

    protected $guard = null;

    protected $setGuard = false;

    /**
     * Get the related column name
     *
     * @return string
     */
    protected function getRelatedColumn()
    {
        return config('setting.related_column', 'user_id');
    }

    /**
     * Check if a user is logged in
     *
     * @return bool
     */
    protected function isGuest()
    {
        return auth($this->getGuard())->guest();
    }

    /**
     * Get current user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function getCurrentUser()
    {
        return auth($this->getGuard())->user();
    }

    /**
     * Belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('setting.user_model', '\\App\\User'));
    }

    /**
     * Search for a user's setting
     *
     * @param string $key
     * @return mixed
     */
    protected function search($key)
    {
        if(!$this->isGuest())
        {
            return self::where($this->getRelatedColumn(), $this->getCurrentUser()->getAuthIdentifier())
                        ->where('key', $key)->first();
        }
        return null;
    }

    protected function getGuard()
    {
        if(!$this->setGuard) {
            $this->guard = config('setting.auth_guard', null);
        }

        return $this->guard;
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
        return $this->search($key)->value ?? $default;
    }

    /**
     * Set the auth guard
     * 
     * @param mixed $guard
     * @return $this
     */
    public function guard($guard)
    {
        $this->guard = $guard;
        $this->setGuard = true;
        return $this;
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
