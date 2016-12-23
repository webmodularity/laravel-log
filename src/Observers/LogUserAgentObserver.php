<?php

namespace WebModularity\LaravelLog\Observers;

use WebModularity\LaravelLog\LogUserAgent;

class LogUserAgentObserver
{
    /**
     * Listen to the LogUserAgent saving event.
     *
     * @param  LogUserAgent  $logUserAgent
     * @return void
     */

    public function saving(LogUserAgent $logUserAgent)
    {
        $logUserAgent->user_agent_hash = LogUserAgent::hashUserAgent($logUserAgent->user_agent);
    }
}
