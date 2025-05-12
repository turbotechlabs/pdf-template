<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default PDF Template Settings
    |--------------------------------------------------------------------------
    |
    | These options control the default settings for the PDF Template package.
    |
    */

    'default_orientation' => 'L',
    'default_page_size' => 'A4',
    'default_format' => 'A4',

    'temp_dir' => public_path('/media/mdf'),

    'default_font' => 'georgia',
    'padding_header' => 100,
    'margin_top' => 28.346456693,
    'margin_left' => 15,
    'margin_right' => 15,

    'view_paths' => [
        'header' => 'pdf-template::header',
        'footer' => 'pdf-template::footer',
    ],

    'landscape_rows_limit' => 16,
    'portrait_rows_limit' => 28,
];
