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
 * @property string $url_query_string
 * @property int $request_method
 * @property int $user_agent_id
 * @property mixed $ip_address
 * @property string $session_id
 * @property string $created_at
 * @property-read \WebModularity\LaravelLog\LogUrlPath $urlPath
 * @property-read \WebModularity\LaravelLog\LogUserAgent $userAgent
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
        'url_query_string',
        'request_method',
        'user_agent_id',
        'ip_address',
        'session_id'
    ];

    public function urlPath()
    {
        return $this->belongsTo('WebModularity\LaravelLog\LogUrlPath');
    }

    public function userAgent()
    {
        return $this->belongsTo('WebModularity\LaravelLog\LogUserAgent');
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
     * @return int|null The ID associated with METHOD_ constant or null if no match found
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
        return null;
    }

    public static function getMethodFromRequest(Request $request)
    {
        return $request->method();
    }

    public static function getSessionIdFromRequest(Request $request)
    {
        return $request->session()->isValidId($request->session()->getId())
            ? $request->session()->getId()
            : null;
    }

    public static function getUrlPathIdFromRequest(Request $request)
    {
        $urlPath = LogUrlPath::firstOrCreate(['url_path' => $request->path()]);
        return !is_null($urlPath) ? $urlPath->id : null;
    }

    public static function getQueryStringIdFromRequest(Request $request)
    {
        $query = $request->query();
        if (empty($query)) {
            return null;
        }
        $queryString = is_array($query) ? http_build_query($query) : $query;
        $queryStringLog = LogQueryString::firstOrCreateFromQueryString($queryString);
        return !is_null($queryStringLog) ? $queryStringLog->id : null;
    }

    public static function getUserAgentIdFromRequest(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        if (empty($userAgent)) {
            return null;
        }
        $userAgentLog = LogUserAgent::firstOrCreateFromUserAgent($userAgent);
        return !is_null($userAgentLog) ? $userAgentLog->id : null;
    }

    public static function getIpAddressFromRequest(Request $request)
    {
        return $request->ip();
    }
}
