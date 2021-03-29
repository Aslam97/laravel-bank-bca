<?php

namespace Aslam\Bca\Facades;

use Illuminate\Support\Facades\Facade;

class BcaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BcaAPI';
    }
}
