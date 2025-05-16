![](example.png)

# Template Class Documentation

A PHP class for generating PDF documents using mPDF with support for Khmer fonts and custom templates.

## Class Properties

- `$viewDir`: Directory path for view templates
- `$fontDir`: Directory path for custom fonts
- `$termDir`: Directory path for temporary files
- `$imageDir`: Directory path for images
- `$defaultHeaderImage`: Default logo image filename
- `$views`: Array of default view templates for header, footer, and body

## Methods

### view(string $viewName, array $data = []): string
Renders a blade template view with provided data.

### config(...$arg): array
Configures mPDF settings with custom fonts and format options. Handles:
- Font directories and custom font definitions for Khmer and Latin fonts
- Page orientation and size settings
- Script and language handling
- Temporary directory location
- Default margins and padding

Example usage:
```php
// Basic usage
$config = Template::config();

// With custom overrides
$config = Template::config(['margin_top' => 30, 'format' => 'A5']);
```

### setHeader($mpdf, $header = null, $headerImage = null): void
Sets the HTML header for the mPDF document.
- `$mpdf`: mPDF instance to set the header on
- `$header`: Optional custom HTML header content. If null, uses default template
- `$headerImage`: Optional path to the header image. Can be absolute path or relative to imageDir

### setFooter($mpdf, $footer = null): void
Sets the HTML footer for the mPDF document.
- `$mpdf`: mPDF instance
- `$footer`: Optional custom HTML footer content. If null, uses default footer template

### setDraft($mpdf, $draft = null): void
Applies a watermark to the PDF document using mPDF.
- `$mpdf`: mPDF instance
- `$draft`: Optional custom watermark text (defaults to 'Draft')
- The watermark uses 'khmerosmoullight' font with 0.1 alpha transparency

### example(Request $request): void
Generates and outputs a PDF document using mPDF with configurable:
- Number of rows (default: 14) and columns (default: 20)
- Page orientation (L/P) (default: 'L')
- Title (default: 'PDF') and headers
- Copy/print protection
- Author and creator metadata
- Dynamic footer positioning based on row count relative to rowLimit

The PDF is directly outputted to the browser with a filename that includes the date and time.

Example usage:
```php
// Basic usage with default title
$controller->example($request);

// Usage with custom title and orientation
$request->merge(['header_title' => 'Custom Report', 'orientation' => 'P']);
$controller->example($request);
```

### useERP(...$args): void
Generates PDF document using ERP template with:
- Customizable headers, title, and company info
- Flexible row limits for landscape/portrait modes (configurable via landscapeRowsLimit/portraitRowsLimit)
- Dynamic footer positioning based on content length
- Automatic title generation with timestamp
- Ability to pass additional parameters through $args to merge with request

The method is similar to example() but intended specifically for ERP template usage 
with a more streamlined approach for content handling.

## Supported Fonts

- khmerosmoullight (Khmer OS Muol Light)
- khmeroscontent (Khmer OS Content)
- content (Regular and Bold)
- timenewroman (Regular, Bold, Italic, Bold Italic)
- ttstandinvoice (Content Times New Roman)

## Example Usage

### Installation

You can install the package via composer:

```bash
composer require smarterp-dev/pdf-template
```

### Basic Usage

```php
<?php
use Turbotech\PDFTemplate\Controllers\Template;
use Illuminate\Http\Request;

Route::get('/pdf', function (Request $request) {
    $total = 14;
    $subHeaderTitle = "As Period: 2023 - 2024";
    $headerTitle = "SUMMARY REPORT ON CASH FLOW FROM OPERATING ACTIVITIES";

    // Example 1: Use the example method
    // return Template::example($request);

    // Example 2: Use the useERP method with string content
    $content = "Hello World!";
    
    // Example 3: Use the useERP method with view content
    $content = view('templates.body', [
        'rows' => $total,
        'cols' => 20,
    ]);

    // Use Template with useERP method
    return Template::useERP([
        'orientation' => 'L', // L = Landscape, P = Portrait
        'header_company' => 'TURBOTECH CO.,LTD',
        'header_title' => $headerTitle,
        'sub_header_title' => $subHeaderTitle,
        'rows' => $total,
        'body' => $content,
        'header_image' => public_path('images/logo.png'), // customize logo
        'landscapeRowsLimit' => 16, // customize row limit for landscape mode
        'portraitRowsLimit' => 28, // customize row limit for portrait mode
    ]);
});
```

### Configuring PDF Settings

You can customize the PDF configuration:

```php
$config = Template::config([
    'orientation' => 'P',
    'format' => 'A5',
    'margin_top' => 30,
    'margin_left' => 20,
    'margin_right' => 20,
]);

$mpdf = new \Mpdf\Mpdf($config);
```

### Adding Watermarks

```php
// Create mPDF instance with configuration
$mpdf = new \Mpdf\Mpdf(Template::config());

// Add a custom watermark
Template::setDraft($mpdf, 'CONFIDENTIAL');

// Or use the default 'Draft' watermark
Template::setDraft($mpdf);
```

### Setting Custom Headers with Images

```php
$mpdf = new \Mpdf\Mpdf(Template::config());

// Set header with custom image
Template::setHeader($mpdf, null, public_path('images/company-logo.png'));

// Set header with custom HTML and image
$customHeader = '<div>Custom header content</div>';
Template::setHeader($mpdf, $customHeader, public_path('images/company-logo.png'));
```

## License
The MIT License (MIT). Please see License File for more information.