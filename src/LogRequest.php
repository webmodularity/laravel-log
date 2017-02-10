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

    public $timestamps = false;

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

    public static function createFromRequest(Request $request)
    {
        static::create([
            'url_path' => LogUrlPath::firstOrCreate(['url_path' => $request->path()]),
            'url_query_string' => $request->query(),
            'request_method' => static::getRequestMethodId($request->method()),
            'user_agent_id' => LogUserAgent::firstOrCreateFromUserAgent($request->header('User-Agent')),
            'ip_address' => inet_pton($request->ip()),
            'session_id' => static::getSessionIdFromRequest($request)
        ]);
    }

    public static function getRequestMethodId($requestMethod)
    {
        $class = new ReflectionClass(__CLASS__);
        foreach ($class->getConstants() as $constantName => $constantValue) {
            if ('METHOD_' . $requestMethod == $constantName) {
                return $constantValue;
            }
        }
        return null;
    }

    public static function getSessionIdFromRequest(Request $request)
    {
        return $request->session()->isValidId($request->session()->getId())
            ? $request->session()->getId()
            : null;
    }
}
