<?php

namespace WebModularity\LaravelLog;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * WebModularity\LaravelLog\LogRequest
 *
 * @property int $id
 * @property int $request_method_id
 * @property int $url_path_id
 * @property int $query_string_id
 * @property int $user_agent_id
 * @property int $ip_address_id
 * @property bool $is_ajax
 * @property string $session_id
 * @property string $created_at
 * @property-read \WebModularity\LaravelLog\LogRequestMethod $requestMethod
 * @property-read \WebModularity\LaravelLog\LogUrlPath $urlPath
 * @property-read \WebModularity\LaravelLog\LogUserAgent $userAgent
 * @property-read \WebModularity\LaravelLog\LogQueryString $queryString
 * @property-read \WebModularity\LaravelLog\LogIpAddress $ipAddress
 */
class LogRequest extends Model
{
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_path_id',
        'request_method_id',
        'query_string_id',
        'user_agent_id',
        'ip_address_id',
        'session_id',
        'is_ajax'
    ];

    public function requestMethod()
    {
        return $this->belongsTo(LogRequestMethod::class);
    }

    public function urlPath()
    {
        return $this->belongsTo(LogUrlPath::class);
    }

    public function userAgent()
    {
        return $this->belongsTo(LogUserAgent::class);
    }

    public function queryString()
    {
        return $this->belongsTo(LogQueryString::class);
    }

    public function ipAddress()
    {
        return $this->belongsTo(LogIpAddress::class);
    }

    /**
     * Helper method used to create new LogRequest model using HTTP Request
     * @param Request $request
     * @return static
     */
    public static function createFromRequest(Request $request)
    {
        return static::firstOrCreate([
            'request_method_id' => static::getRequestMethodId($request->method()),
            'url_path_id' => static::getUrlPathId($request->path()),
            'query_string_id' => static::getQueryStringId($request->query()),
            'user_agent_id' => static::getUserAgentId($request->header('User-Agent')),
            'ip_address_id' => static::getIpAddressId($request->ip()),
            'session_id' => static::getSessionId($request->session()),
            'is_ajax' => $request->ajax()
        ]);
    }

    public static function getRequestMethodId($method)
    {
        return LogRequestMethod::where('method', $method)->first()->id
            ?: LogRequestMethod::where('method', 'GET')->first()->id;
    }

    public static function getIpAddressId($ipAddress)
    {
        return LogIpAddress::firstOrCreate(['ip' => $ipAddress])->id;
    }

    public static function getUrlPathId($urlPath)
    {
        return LogUrlPath::firstOrCreate(
            ['url_path' => $urlPath]
        )->id;
    }

    public static function getQueryStringId($query)
    {
        if (empty($query)) {
            return null;
        }
        $queryString = is_array($query) ? http_build_query($query) : $query;

        return LogQueryString::firstOrCreate(
            ['query_string_hash' => LogQueryString::hashQueryString($queryString)],
            ['query_string' => $queryString]
        )
            ->id;
    }

    public static function getUserAgentId($userAgent)
    {
        if (empty($userAgent)) {
            return null;
        }

        return LogUserAgent::firstOrCreate(
            ['user_agent_hash' => LogUserAgent::hashUserAgent($userAgent)],
            ['user_agent' => $userAgent]
        )
            ->id;
    }

    public static function getSessionId(Session $session)
    {
        return $session->isValidId($session->getId())
            ? $session->getId()
            : null;
    }
}
