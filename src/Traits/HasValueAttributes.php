<?php

namespace TaylorNetwork\Setting\Traits;

trait HasValueAttributes
{
    public function getValueAttribute()
    {
        $value = $this->attributes['value'];

        if (strtolower($value) === 'true' || strtolower($value) === 'false') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if (ctype_digit($value)) {
            return (int) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return $value;
    }

    public function setValueAttribute($value)
    {
        if (gettype($value) === 'boolean') {
            $this->attributes['value'] = $value ? 'true' : 'false';
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }

    public function getRawValueAttribute()
    {
        return $this->attributes['value'];
    }
}
