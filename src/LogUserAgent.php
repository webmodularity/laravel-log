<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

class LogUserAgent extends Model
{
    public $timestamps = false;

    /**
     * Creates a hash from user_agent string
     *
     * @param $userAgent string The user_agent string sent by browser during http request
     * @return string Hash of user agent in binary format
     */

    public static function hashUserAgent($userAgent)
    {
        return hex2bin(md5($userAgent));
    }
}
