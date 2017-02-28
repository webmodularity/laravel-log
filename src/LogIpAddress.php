<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

/**
 * WebModularity\LaravelLog\LogIpAddress
 *
 * @property int $id
 * @property mixed $ip
 */
class LogIpAddress extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip'];

    public function getIpAttribute($value)
    {
        return static::decryptIpAddress($value);
    }

    public function setIpAttribute($value)
    {
        $this->attributes['ip'] = static::encryptIpAddress($value);
    }

    public static function decryptIpAddress($ipAddress)
    {
        return inet_ntop($ipAddress);
    }

    public static function encryptIpAddress($ipAddress)
    {
        return inet_pton($ipAddress);
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function firstOrCreate(array $attributes, array $values = [])
    {
        $parsedAttributes = [];
        foreach ($parsedAttributes as $key => $value) {
            if ($key == 'ip') {
                $parsedAttributes[$key] = static::encryptIpAddress($value);
            } else {
                $parsedAttributes[$key] = $value;
            }
        }

        if (! is_null($instance = static::where($parsedAttributes)->first())) {
            return $instance;
        }
        $instance = static::newInstance($attributes + $values);
        $instance->save();
        return $instance;
    }
}
