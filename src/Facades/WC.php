<?php
namespace Mindsize\WC\Facades;

use Illuminate\Support\Facades\Facade;

class WC extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wc';
    }
}
