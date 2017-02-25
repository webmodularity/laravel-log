<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

/**
 * WebModularity\LaravelLog\LogIpAddress
 *
 * @property int $id
 * @property mixed $ip_address
 */
class LogIpAddress extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip_address'];

    public function getIpAddressAttribute($value)
    {
        return static::decryptIpAddress($value);
    }

    public function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = static::encryptIpAddress($value);
    }

    public static function decryptIpAddress($ipAddress)
    {
        return inet_ntop($ipAddress);
    }

    public static function encryptIpAddress($ipAddress)
    {
        return inet_pton($ipAddress);
    }
}
