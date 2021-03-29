<?php

use Aslam\Bca\Bca;

if (!function_exists('bcaapi')) {

    /**
     * bcaapi
     *
     * @return Bca
     */
    function bcaapi()
    {
        return app(Bca::class);
    }
}
