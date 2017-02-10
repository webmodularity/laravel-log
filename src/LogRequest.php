<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

/**
 * WebModularity\LaravelLog\LogRequest
 *
 * @property int $id
 * @property int $url_path_id
 * @property string $url_query_string
 * @property int $user_agent_id
 * @property mixed $ip_address
 * @property string $session_id
 * @property string $created_at
 * @property-read \WebModularity\LaravelLog\LogUrlPath $urlPath
 * @property-read \WebModularity\LaravelLog\LogUserAgent $userAgent
 */
class LogRequest extends Model
{
    public $timestamps = false;

    public function urlPath()
    {
        return $this->belongsTo('WebModularity\LaravelLog\LogUrlPath');
    }

    public function userAgent()
    {
        return $this->belongsTo('WebModularity\LaravelLog\LogUserAgent');
    }
}
