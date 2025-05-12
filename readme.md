# Turbotech PDF Template

A Laravel package for generating PDF documents using mPDF with built-in support for Khmer fonts and responsive layouts.

## Installation

You can install the package via composer:

```bash
composer require turbotech/pdf-template
```

## Usage

```php
<?php
use Turbotech\PDFTemplate\PDFTemplate;

// Generate a basic PDF
PDFTemplate::useERP([
    'header_title' => 'My Document',
    'header_company' => 'My Company',
    'sub_header_title' => 'Period: January 2023',
    'body' => '<h1>Hello World</h1><p>This is my PDF content</p>'
]);

// With custom configuration
$config = PDFTemplate::config([
    'margin_top' => 30,
    'format' => 'A5'
]);

// Add watermark
$mpdf = new \Mpdf\Mpdf($config);
PDFTemplate::setDraft($mpdf, 'CONFIDENTIAL');
```

## Configuration
Publish the configuration file:

```php
php artisan vendor:publish --provider="Turbotech\PDFTemplate\PDFTemplateServiceProvider" --tag="config"
```

## Fonts

This package includes several Khmer fonts by default. To publish them to your public directory:


```php
php artisan vendor:publish --provider="Turbotech\PDFTemplate\PDFTemplateServiceProvider" --tag="fonts"
```

## License
The MIT License (MIT). Please see License File for more information.

## Create a Git Repository

Initialize a Git repository in your package directory:

```bash
git init
git add .
git commit -m "Initial commit"
```
