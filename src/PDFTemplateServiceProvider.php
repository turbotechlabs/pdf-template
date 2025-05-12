<?php

namespace Turbotech\PDFTemplate;

use Illuminate\Support\ServiceProvider;

class PDFTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish views
        $this->publishes([
            __DIR__ . '/../views/example/' => resource_path('views/pdf-template'),
        ], 'views');

        // Load views
        // $this->loadViewsFrom(__DIR__ . '/../src/views/ex', 'pdf-template');
    }
}
