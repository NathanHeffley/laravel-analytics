<?php

namespace NathanHeffley\Analytics\Http\Controllers;

use NathanHeffley\Analytics\Pageview;

class AnalyticsController extends Controller
{
    public function index()
    {
        $pageviews = Pageview::all()->count();

        return view('analytics::index', compact('pageviews'));
    }
}
