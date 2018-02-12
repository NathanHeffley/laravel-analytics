<?php

namespace NathanHeffley\Analytics\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use NathanHeffley\Analytics\AnalyticsFacade as Analytics;

class Pageview
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        Analytics::record('pageview', [
            'user' => Auth::user(),
            'path' => $request->path(),
        ]);

        return $next($request);
    }
}
