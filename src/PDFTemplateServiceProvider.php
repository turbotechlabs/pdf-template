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
        $viewDir = __DIR__.'/views';
        $templateDir = __DIR__.'/views/templates';
        $exampleDir = __DIR__.'/views/templates/examples';

        if ($this->app->runningInConsole()) {
            /**
             * Publishes the package's view files to the application's resources directory.
             *
             * Run the vendor:publish Artisan command with the "views" tag to copy
             * the package's views to your application's "resources/views/vendor/smarterp-dev" directory.
             *
             * @uses  php artisan vendor:publish --tag=views
             */
            $this->publishes([ $viewDir => resource_path('views/vendor/smarterp-dev'), ], 'views');


            /**
             * Publishes the package's PDF template views to the application's resources directory.
             *
             * @uses php artisan vendor:publish --tag=view-templates
             */
            $this->publishes([ $templateDir => resource_path('views/vendor/smarterp-dev/templates'), ], 'view-templates');


            /**
             * Publishes example PDF template views to the application's resources directory.
             *
             * @uses php artisan vendor:publish --tag=view-examples
             */
            $this->publishes([ $exampleDir => resource_path('views/vendor/smarterp-dev/templates/examples'), ], 'view-examples');
        }

        // Load package views
        $this->loadViewsFrom($viewDir, 'smarterp-dev');
    }
}
