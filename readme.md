![](./src//images/pre.png)

# Template Class Documentation

A PHP class for generating PDF documents using mPDF with support for Khmer fonts and custom templates.

## Class Properties

- `$viewDir`: Directory path for view templates
- `$fontDir`: Directory path for custom fonts
- `$termDir`: Directory path for temporary files
- `$imageDir`: Directory path for images
- `$defaultHeaderImage`: Default logo image filename
- `$views`: Array of default view templates for header, footer, and body

## Testing

This package includes a comprehensive test suite using PHPUnit to verify all functionality works correctly. The tests cover configuration, PDF generation, and font handling to ensure reliability across different environments.

### Prerequisites

Before running tests, make sure you have:

1. Installed all development dependencies:
   ```bash
   composer install
   ```

2. Created the required temporary directory for mPDF:
   ```bash
   mkdir -p temp/pdf
   ```

### Running Tests

You can run the test suite using any of these methods:

1. Using Composer script (recommended):
   ```bash
   composer test
   ```

2. Running PHPUnit directly:
   ```bash
   vendor/bin/phpunit
   ```

3. Running specific test suite:
   ```bash
   vendor/bin/phpunit --testsuite=Unit
   ```

4. Running a specific test file:
   ```bash
   vendor/bin/phpunit tests/Unit/TemplateTest.php
   ```

5. Running a specific test method:
   ```bash
   vendor/bin/phpunit --filter=it_can_get_config_with_default_values
   ```

### Test Coverage Report

To generate a detailed HTML test coverage report, run:

```bash
composer test-coverage
```

This will create HTML reports in the `coverage` directory that you can open in your browser to see which parts of the code are covered by tests.

### Continuous Integration

This package includes GitHub Actions workflow configuration in `.github/workflows/run-tests.yml` that automatically runs tests across multiple PHP versions (7.4, 8.0, 8.1) and Laravel versions (8.*) whenever changes are pushed to the `master` or `develop` branches.

### Extending Tests

When adding new features to the package, please also add corresponding tests to maintain code quality and prevent regressions. Tests should be placed in the appropriate directory:

- Unit tests: `tests/Unit/`

## Methods

### view(string $viewName, array $data = []): string
Renders a blade template view with provided data.

```php
// Example usage
$renderedView = Template::view('body.blade.php', ['rows' => 10, 'cols' => 5]);
```

### config(...$arg): array
Configures mPDF settings with custom fonts and format options.

This method sets up the configuration for mPDF including:
- Font directories and custom font definitions for Khmer and Latin fonts
- Page orientation and size settings
- Script and language handling
- Temporary directory location
- Default margins and padding

```php
// Basic usage
$config = Template::config();

// With custom overrides
$config = Template::config(['margin_top' => 30, 'format' => 'A5']);
```

### setHeader($mpdf, $header = null, $headerImage = null): void
Sets the HTML header for the mPDF document.

- `$mpdf`: The mPDF instance to set the header on
- `$header`: Optional custom HTML header content. If null, uses default template
- `$headerImage`: Path to the header image (relative to imageDir or absolute)

```php
// Set default header with custom image
Template::setHeader($mpdf, null, 'custom-logo.png');

// Set completely custom header HTML
Template::setHeader($mpdf, '<div>My Custom Header</div>');
```

### setFooter($mpdf, $footer = null): void
Sets the footer for the PDF document.

- `$mpdf`: The mPDF instance
- `$footer`: Optional HTML footer content. If null, uses default footer template

```php
// Set default footer
Template::setFooter($mpdf);

// Set custom footer HTML
Template::setFooter($mpdf, '<div>Page {PAGENO} of {nbpg}</div>');
```

### setDraft($mpdf, $draft = null): void
Set watermark text on PDF document.

- `$mpdf`: The mPDF object instance
- `$draft`: Custom watermark text (optional, defaults to 'Draft')

The watermark uses 'khmerosmoullight' font with 0.1 alpha transparency.

```php
// Set default "Draft" watermark
Template::setDraft($mpdf);

// Set custom watermark text
Template::setDraft($mpdf, 'CONFIDENTIAL');
```

### example(Request $request): void
Generates and outputs a PDF document using mPDF.

This method creates a PDF document with configurable margins, title and author.
It can include header, body content from views, and footer.
The PDF is directly outputted to the browser with copy and print protection.

Parameters from Request:
- `rows`: Number of rows (default: 14)
- `cols`: Number of columns (default: 20)
- `header_title`: PDF title (default: 'PDF')
- `orientation`/`o`: Page orientation L/P (default: 'L')
- `header_company`: Company name for header (default: 'TURBOTECH CO.,LTD')
- `sub_header_title`: Period or subtitle text
- `header_image`: Custom logo path (uses default if not specified)

```php
// Basic usage with default title
Template::example($request);

// Usage with custom title and orientation
$request->merge(['header_title' => 'Custom Report', 'orientation' => 'P']);
Template::example($request);
```

### useERP(...$args): void
Generate PDF document using ERP template.

Similar to `example()` but with more flexibility for ERP usage. Allows:
- Customizable headers, title, and company info
- Flexible row limits for landscape/portrait modes (configurable via landscapeRowsLimit/portraitRowsLimit)
- Dynamic footer positioning based on content length
- Automatic title generation with timestamp
- Direct body content provision through 'body' parameter
- Ability to pass additional parameters through $args to merge with request

```php
// Basic usage with HTML content
Template::useERP([
    'orientation' => 'L',
    'header_title' => 'Sales Report',
    'sub_header_title' => 'Q1 2025',
    'rows' => 15,
    'body' => '<table>...</table>',
    'landscapeRowsLimit' => 20
]);

// Using with view-rendered content
$body = view('reports.sales', ['data' => $salesData])->render();
Template::useERP([
    'body' => $body,
    'header_title' => 'Sales Summary'
]);
```

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