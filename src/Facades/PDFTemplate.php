<?php

namespace Turbotech\Facades;

use Illuminate\Support\Facades\Facade;

class PDFTemplate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pdf-template';
    }
}