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
            __DIR__.'/views' => resource_path('views/vendor/pdf-template'),
        ], 'pdf-template-views');

        // Load package views
        $this->loadViewsFrom(__DIR__.'/views', 'pdf-template');
    }
}
