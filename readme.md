![](./src//images/pre.png)

[![PHP Tests](https://github.com/turbotechlabs/pdf-template/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/turbotechlabs/pdf-template/actions/workflows/run-tests.yml)

# Template Class Documentation

A PHP class for generating PDF documents using mPDF with support for Khmer fonts and custom templates.

## Class Properties

The Template class contains several protected static properties that define the package structure:

- `$viewDir`: Directory path for view templates (`__DIR__ . '/../views/example/'`)
- `$fontDir`: Directory path for custom fonts (`__DIR__ . '/../fonts/'`)
- `$termDir`: Directory path for temporary files (`__DIR__ . '/../../temp/pdf'`)
- `$imageDir`: Directory path for images (`__DIR__ . '/../images/'`)
- `$defaultHeaderImage`: Default logo image filename (`'logo.png'`)
- `$views`: Array of default view templates:
  - `'header'` → `'header.blade.php'`
  - `'footer'` → `'footer.blade.php'`
  - `'body'` → `'body.blade.php'`

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
Configures the PDF settings for mPDF.

This static method sets up the configuration for mPDF, including font directories, 
default fonts, page orientation, and other parameters. It merges any additional 
arguments provided into the configuration array.

Features:
- Font directories and custom font definitions for Khmer and Latin fonts
- Page orientation and size settings (supports A4, custom formats)
- Script and language handling (+aCJK mode for Asian languages)
- Temporary directory location configuration
- Default margins and padding settings
- Custom font data including Khmer fonts with OpenType Layout support

```php
// Basic usage
$config = Template::config();

// With custom overrides
$config = Template::config(['margin_top' => 30, 'format' => 'A5']);

// Multiple configuration arrays
$config = Template::config(
    ['orientation' => 'P'],
    ['margin_bottom' => 20, 'default_font' => 'timenewroman']
);
```

### setHeader($mpdf, $header = null, $headerImage = null): void
Sets the header for the PDF document.

- `$mpdf`: The mPDF instance
- `$header`: Optional HTML header content. If null, uses default header template
- `$headerImage`: Optional path to a header image. If null, uses default image

This method handles automatic image path resolution - it checks if the provided path 
is absolute, falls back to the package's image directory, or uses the default logo.

```php
// Set default header with package's default logo
Template::setHeader($mpdf);

// Set default header with custom image
Template::setHeader($mpdf, null, 'custom-logo.png');

// Set completely custom header HTML
Template::setHeader($mpdf, '<div style="text-align: center;">My Custom Header</div>');

// Set custom header with specific image path
Template::setHeader($mpdf, null, '/absolute/path/to/logo.png');
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
Generate a PDF document with example content.

This static method creates a PDF based on the provided request parameters, 
allowing customization of rows, columns, header title, and orientation. 
It supports both landscape and portrait orientations with dynamic row limits.

Features:
- Dynamic row limit calculation (16 for landscape, 28 for portrait)
- Automatic margin adjustment based on content length
- Copy and print protection with password 'pass'
- PDF compression enabled
- Automatic filename generation with timestamp
- Dynamic footer placement logic

Parameters from Request:
- `rows`: Number of rows (default: 14)
- `cols`: Number of columns (default: 20)
- `header_title`: PDF title (default: 'PDF')
- `orientation`: Page orientation L/P (default: 'L')
- `header_company`: Company name for header (default: 'TURBOTECH CO.,LTD')
- `sub_header_title`: Period or subtitle text
- `header_image`: Custom logo path (uses default if not specified)

```php
// Basic usage with default settings
Template::example($request);

// Usage with custom parameters
$request->merge([
    'header_title' => 'Monthly Sales Report',
    'orientation' => 'P',
    'rows' => 20,
    'cols' => 8,
    'header_company' => 'My Company Ltd.',
    'sub_header_title' => 'January 2025'
]);
Template::example($request);
```

### useERP(...$args): void
Generate a PDF document using ERP template settings.

This static method creates a PDF based on configuration settings and content provided 
via the request or additional arguments. It supports both landscape and portrait orientations 
with dynamic row limits.

Features:
- Allows additional parameter merging through variadic arguments
- Customizable header with company name, title, and image
- Automatic page orientation handling (landscape/portrait)
- Dynamic margin adjustment based on content length
- Automatic footer placement logic
- PDF metadata (title, author, creator)
- Configurable row limits for different orientations
- Direct body content provision through 'body' parameter

Parameters:
- `...$args`: Additional parameters to merge with the current request
- `body`: HTML content for the PDF body
- `header_title`: PDF title (default: 'PDF')
- `header_company`: Company name (default: 'TURBOTECH CO.,LTD')
- `sub_header_title`: Period or subtitle text
- `header_image`: Custom logo path
- `orientation`: Page orientation L/P (default: 'L')
- `rows`: Number of rows for dynamic footer logic (default: 14)
- `landscapeRowsLimit`: Custom row limit for landscape mode (default: 16)
- `portraitRowsLimit`: Custom row limit for portrait mode (default: 28)

```php
// Basic usage with HTML content
Template::useERP([
    'orientation' => 'L',
    'header_title' => 'Sales Report',
    'sub_header_title' => 'Q1 2025',
    'rows' => 15,
    'body' => '<table><tr><td>Sales Data</td></tr></table>',
    'landscapeRowsLimit' => 20
]);

// Using with view-rendered content
$body = view('reports.sales', ['data' => $salesData])->render();
Template::useERP([
    'body' => $body,
    'header_title' => 'Sales Summary',
    'header_company' => 'ABC Corporation',
    'orientation' => 'P',
    'portraitRowsLimit' => 30
]);

// Multiple parameter arrays
Template::useERP(
    ['orientation' => 'L', 'header_title' => 'Report'],
    ['body' => $htmlContent, 'rows' => 25]
);
```

## Supported Fonts

The package includes comprehensive font support for both Khmer and Latin scripts:

### Khmer Fonts
- **khmerosmoullight** - Khmer OS Muol Light (`Khmer-OS-Muol-Light.ttf`)
- **khmeroscontent** - Khmer OS Content (`KhmerOS_content.ttf`)

### Latin Fonts  
- **content** - Content Regular and Bold (`Content-Regular.ttf`, `Content-Bold.ttf`)
- **timenewroman** - Complete Times New Roman family:
  - Regular (`times_new_roman.ttf`)
  - Bold (`times_new_roman_bold.ttf`) 
  - Italic (`times_new_roman_italic.ttf`)
  - Bold Italic (`times_new_roman_bold_italic.ttf`)
- **ttstandinvoice** - Content Times New Roman OpenType (`Content-TimesNewRoman.otf`)

### Default Configuration
- **Default font**: `georgia`
- **OpenType Layout (OTL)**: Enabled for all custom fonts (`useOTL => 0xFF`)
- **Mode**: `+aCJK` for Asian, Chinese, Japanese, Korean language support

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
    $subHeaderTitle = "As Period: 2024 - 2025";
    $headerTitle = "SUMMARY REPORT ON CASH FLOW FROM OPERATING ACTIVITIES";

    // Example 1: Use the example method for predefined template
    return Template::example($request->merge([
        'header_title' => $headerTitle,
        'sub_header_title' => $subHeaderTitle,
        'rows' => $total,
        'cols' => 20,
        'orientation' => 'L'
    ]));

    // Example 2: Use the useERP method with HTML content
    $htmlContent = '<div><h2>Financial Summary</h2><p>Content here...</p></div>';
    
    return Template::useERP([
        'orientation' => 'L', // L = Landscape, P = Portrait
        'header_company' => 'TURBOTECH CO.,LTD',
        'header_title' => $headerTitle,
        'sub_header_title' => $subHeaderTitle,
        'rows' => $total,
        'body' => $htmlContent,
        'header_image' => public_path('images/logo.png'), // customize logo
        'landscapeRowsLimit' => 16, // customize row limit for landscape mode
        'portraitRowsLimit' => 28, // customize row limit for portrait mode
    ]);

    // Example 3: Use the useERP method with view-rendered content
    $content = view('templates.financial-report', [
        'rows' => $total,
        'cols' => 20,
        'data' => $financialData
    ])->render();

    return Template::useERP([
        'body' => $content,
        'header_title' => $headerTitle,
        'orientation' => 'P'
    ]);
});
```

### Configuring PDF Settings

You can customize the PDF configuration using the config method:

```php
// Basic configuration
$config = Template::config();

// Custom configuration with overrides
$config = Template::config([
    'orientation' => 'P',
    'format' => 'A5',
    'margin_top' => 30,
    'margin_left' => 20,
    'margin_right' => 20,
    'default_font' => 'timenewroman'
]);

// Multiple configuration arrays
$config = Template::config(
    ['orientation' => 'L'],
    ['margin_bottom' => 25, 'padding_header' => 80]
);

// Use with mPDF
$mpdf = new \Mpdf\Mpdf($config);
```

### Available Configuration Options

- `orientation`: Page orientation ('L' for landscape, 'P' for portrait)
- `format`/`page_size`: Page format (default: 'A4')
- `margin_top`: Top margin (default: 28.346456693)
- `margin_left`: Left margin (default: 15)
- `margin_right`: Right margin (default: 15)
- `margin_bottom`: Bottom margin (calculated dynamically)
- `default_font`: Default font (default: 'georgia')
- `padding_header`: Header padding (default: 100)
- `tempDir`: Temporary directory for mPDF files

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