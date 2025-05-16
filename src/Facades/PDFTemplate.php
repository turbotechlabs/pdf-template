<?php

namespace Turbotech\PDFTemplate\Facades;

use Illuminate\Support\Facades\Facade;

class PDFTemplate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pdf-template';
    }
}