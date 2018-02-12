<?php

namespace NathanHeffley\Analytics;

use Illuminate\Support\Facades\Facade;

class AnalyticsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Analytics';
    }
}