<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

/**
 * WebModularity\LaravelLog\LogUserAgent
 *
 * @property int $id
 * @property string $user_agent
 * @property mixed $user_agent_hash
 */
class LogUserAgent extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_agent', 'user_agent_hash'];

//

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

    /**
     * Takes a User-Agent string and returns the associated LogUserAgent Model.
     * Will create new record if no LogUserAgent is found.
     * @param string $userAgentString The User-Agent string fetched from Request
     * @return static|null
     */
    public static function firstOrCreateFromUserAgent($userAgentString)
    {
        if (empty($userAgentString)) {
            return null;
        }

        return static::firstOrCreate(
            ['user_agent_hash' => static::hashUserAgent($userAgentString)],
            ['user_agent' => $userAgentString]
        );
    }
}
