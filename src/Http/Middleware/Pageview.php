<?php

namespace NathanHeffley\Analytics\Http\Middleware;

use NathanHeffley\Analytics\AnalyticsFacade as Analytics;

class Pageview
{
    public function handle($request, $next)
    {
        Analytics::record('pageview', [
            'path' => '/example-path',
        ]);

        return $next($request);
    }
}
