<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

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
