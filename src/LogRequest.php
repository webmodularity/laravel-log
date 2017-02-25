<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ReflectionClass;

/**
 * WebModularity\LaravelLog\LogRequest
 *
 * @property int $id
 * @property int $url_path_id
 * @property int $request_method
 * @property string $query_string_id
 * @property int $user_agent_id
 * @property mixed $ip_address
 * @property string $session_id
 * @property string $created_at
 * @property-read \WebModularity\LaravelLog\LogUrlPath $urlPath
 * @property-read \WebModularity\LaravelLog\LogUserAgent $userAgent
 * @property-read \WebModularity\LaravelLog\LogQueryString $queryString
 */
class LogRequest extends Model
{
    const METHOD_GET = 1;
    const METHOD_POST = 2;

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_path_id',
        'request_method',
        'query_string_id',
        'user_agent_id',
        'ip_address',
        'session_id'
    ];

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


    public function getIpAddressAttribute($value)
    {
        return inet_ntop($value);
    }

    public function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = inet_pton($value);
    }

    /**
     * Helper method used to create new LogRequest model using HTTP Request
     * @param Request $request
     * @return static
     */
    public static function createFromRequest(Request $request)
    {
        return static::firstOrCreate([
            'request_method' => static::getRequestMethodIdFromRequest($request),
            'url_path_id' => static::getUrlPathIdFromRequest($request),
            'query_string_id' => static::getQueryStringIdFromRequest($request),
            'user_agent_id' => static::getUserAgentIdFromRequest($request),
            'ip_address' => static::getIpAddressFromRequest($request),
            'session_id' => static::getSessionIdFromRequest($request)
        ]);
    }

    /**
     * Attempts to convert method passed from HTTP Request into ID stored in constants
     * starting with METHOD_ for storage.
     * @param Request $request
     * @return int The ID associated with METHOD_ constant or METHOD_GET by default
     */
    public static function getRequestMethodIdFromRequest(Request $request)
    {
        $class = new ReflectionClass(__CLASS__);
        $requestMethod = static::getMethodFromRequest($request);
        foreach ($class->getConstants() as $constantName => $constantValue) {
            if ('METHOD_' . $requestMethod == $constantName) {
                return $constantValue;
            }
        }
        return static::METHOD_GET;
    }

    public static function getMethodFromRequest(Request $request)
    {
        return $request->method();
    }

    public static function getSessionIdFromRequest(Request $request)
    {
        return $request->session()->isValidId($request->session()->getId())
            ? $request->session()->getId()
            : '';
    }

    public static function getUrlPathIdFromRequest(Request $request)
    {
        return LogUrlPath::firstOrCreate(['url_path' => $request->path()])->id;
    }

    public static function getQueryStringIdFromRequest(Request $request)
    {
        $query = $request->query() ?: '';
        $queryString = is_array($query) ? http_build_query($query) : $query;
        return LogQueryString::firstOrCreateFromQueryString($queryString)->id;
    }

    public static function getUserAgentIdFromRequest(Request $request)
    {
        $userAgent = $request->header('User-Agent') ?: '';
        return LogUserAgent::firstOrCreateFromUserAgent($userAgent);
    }

    public static function getIpAddressFromRequest(Request $request)
    {
        return $request->ip();
    }
}
