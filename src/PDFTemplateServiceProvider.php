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
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pdf-template.php',
            'pdf-template'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/pdf-template.php' => config_path('pdf-template.php'),
        ], 'config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../src/views' => resource_path('views/vendor/pdf-template'),
        ], 'views');

        // Publish fonts
        $this->publishes([
            __DIR__ . '/../fonts' => public_path('fonts'),
        ], 'fonts');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../src/views', 'pdf-template');
    }
}
