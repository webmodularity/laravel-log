<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * WebModularity\LaravelLog\LogRequest
 *
 * @property int $id
 * @property int $request_method_id
 * @property int $url_path_id
 * @property string $query_string_id
 * @property int $user_agent_id
 * @property mixed $ip_address
 * @property bool $is_ajax
 * @property string $session_id
 * @property string $created_at
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
            'request_method_id' => static::getRequestMethodIdFromRequest($request),
            'url_path_id' => static::getUrlPathIdFromRequest($request),
            'query_string_id' => static::getQueryStringIdFromRequest($request),
            'user_agent_id' => static::getUserAgentIdFromRequest($request),
            'ip_address_id' => static::getIpAddressIdFromRequest($request),
            'session_id' => static::getSessionIdFromRequest($request),
            'is_ajax' => $request->ajax()
        ]);
    }

    public static function getRequestMethodIdFromRequest(Request $request)
    {
        return LogRequestMethod::where('method', $request->method())->first()->id
            ?: LogRequestMethod::where('method', 'GET')->first()->id;
    }

    public static function getSessionIdFromRequest(Request $request)
    {
        return $request->session()->isValidId($request->session()->getId())
            ? $request->session()->getId()
            : null;
    }

    public static function getIpAddressIdFromRequest(Request $request)
    {
        $ipAddress = $request->ip();
        $logIpAddress = LogIpAddress::where('ip_address', LogIpAddress::encryptIpAddress($ipAddress))->first();
        return !is_null($logIpAddress)
            ? $logIpAddress->id
            : LogIpAddress::create(['ip_address' => $ipAddress])->id;
    }

    public static function getUrlPathIdFromRequest(Request $request)
    {
        return LogUrlPath::firstOrCreate(['url_path' => $request->path()])->id;
    }

    public static function getQueryStringIdFromRequest(Request $request)
    {
        $query = $request->query();
        if (empty($query)) {
            return null;
        }
        $queryString = is_array($query) ? http_build_query($query) : $query;
        return LogQueryString::firstOrCreateFromQueryString($queryString)->id;
    }

    public static function getUserAgentIdFromRequest(Request $request)
    {
        $userAgent = $request->header('User-Agent') ?: '';
        return LogUserAgent::firstOrCreateFromUserAgent($userAgent)->id;
    }
}
