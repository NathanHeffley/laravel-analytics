<?php

namespace NathanHeffley\Analytics;

use Illuminate\Support\Facades\Facade;

class PageviewFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Pageview';
    }
}