<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

/**
 * WebModularity\LaravelLog\LogQueryString
 *
 * @property int $id
 * @property string $query_string
 * @property mixed $query_string_hash
 */
class LogQueryString extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['query_string', 'query_string_hash'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = ['query_string_hash'];

    /**
     * Creates a hash from query string
     * @param $queryString string The query string sent by browser during http request
     * @return mixed Hash of query string in binary format
     */
    public static function hashQueryString($queryString)
    {
        return hex2bin(md5($queryString));
    }

    /**
     * Takes a query string and returns the associated LogQueryString Model.
     * Will create new record if no LogQueryString is found.
     * @param string $queryString The query string from Request
     * @return static|null
     */
    public static function firstOrCreateFromQueryString($queryString)
    {
        if (empty($queryString)) {
            return null;
        }

        return static::firstOrCreate(
            ['query_string_hash' => static::hashQueryString($queryString)],
            ['query_string' => $queryString]
        );
    }
}
