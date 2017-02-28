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
     * Creates a hash from query string
     * @param $queryString string The query string sent by browser during http request
     * @return mixed Hash of query string in binary format
     */
    public static function hashQueryString($queryString)
    {
        return hex2bin(md5($queryString));
    }
}
