## PDF Class Usage Example

### Basic Instantiation and Configuration

```php
use Turbotech\PDFTemplate\PDFs\PDF;

// Create with default options
$pdf = new PDF();
$config = $pdf->config();
print_r($config); // Default orientation: 'P', format: 'A4', default_font: 'georgia'

// Override options
$pdfCustom = new PDF(['orientation' => 'L', 'format' => 'A5', 'default_font' => 'timenewroman']);
$configCustom = $pdfCustom->config();
print_r($configCustom); // orientation: 'L', format: 'A5', default_font: 'timenewroman'
```

### Directory and Path Methods

```php
$pdf = new PDF();
$pdf->getFileTempDir(); // Returns default or custom temp dir
$pdf->getFontDir();     // Returns default or custom font dir array
$pdf->getImageDir();    // Returns default or custom image dir
$pdf->getViewsDir();    // Returns default or custom views dir
$pdf->getViewTemplatesDir(); // Returns templates dir
$pdf->getViewTemplateByName('invoice'); // Returns full path to invoice template
$pdf->getViewExamplesDir(); // Returns examples dir
$pdf->getViewExamplesByName('sample'); // Returns full path to sample example
$pdf->logo(); // Returns logo path
```

### Rendering HTML Sections

```php
$pdf = new PDF([
    'header' => '<header>Test</header>',
    'body' => '<body>Test</body>',
    'footer' => '<footer>Test</footer>'
]);
$pdf->renderHeaderHTML(); // Returns header HTML string
$pdf->renderBodyHTML();   // Returns body HTML string
$pdf->renderFooterHTML(); // Returns footer HTML string
```

### Watermark Example

```php
$pdf = new PDF([
    'showWatermark' => true,
    'watermarkText' => 'CONFIDENTIAL',
    'watermarkTextAlpha' => 0.5,
    'watermarkFontFamily' => 'timenewroman'
]);
$mpdf = new \Mpdf\Mpdf($pdf->config());
$pdf->setWatermark($mpdf); // Sets watermark properties on mPDF instance
```

### Rendering the PDF

```php
$pdf = new PDF([
    'rows' => 10,
    'orientation' => 'L',
    'headerTitle' => 'Report',
    'body' => '<div>Body Content</div>',
    'footerText' => 'Custom Footer'
]);
$pdf->render(); // Outputs the PDF to browser
```

### Font Configuration and Customization

```php
$pdf = new PDF();
$config = $pdf->config();
$fontData = $config['fontdata'];
// Check available fonts
var_dump(array_keys($fontData)); // ['khmerosmoullight', 'khmeroscontent', 'content', 'timenewroman', 'ttstandinvoice', ...]
```

### Custom Directories

```php
$pdf = new PDF(['tempDir' => '/custom/temp', 'fontDir' => '/custom/fonts', 'imageDir' => '/custom/images', 'views' => '/custom/views']);
$pdf->getFileTempDir(); // '/custom/temp'
$pdf->getFontDir();     // ['/custom/fonts']
$pdf->getImageDir();    // '/custom/images'
$pdf->getViewsDir();    // '/custom/views'
```

### Custom Logo

```php
$pdf = new PDF(['logo' => '/custom/logo.png']);
$pdf->logo(); // '/custom/logo.png'
```

### Custom Templates

```php
$pdf = new PDF();
$pdf->getViewTemplateByName('invoice'); // Returns invoice template path
$pdf->getViewExamplesByName('sample');  // Returns sample example path
```

### Header, Body, Footer Rendering with Custom Content

```php
$pdf = new PDF(['header' => '<header>Header</header>']);
echo $pdf->renderHeaderHTML(); // <header>Header</header>

$pdf = new PDF(['body' => '<body>Body</body>']);
echo $pdf->renderBodyHTML(); // <body>Body</body>

$pdf = new PDF(['footer' => '<footer>Footer</footer>']);
echo $pdf->renderFooterHTML(); // <footer>Footer</footer>
```

### Watermark Properties

```php
$pdf = new PDF([
    'showWatermark' => true,
    'watermarkText' => 'CONFIDENTIAL',
    'watermarkTextAlpha' => 0.5,
    'watermarkFontFamily' => 'timenewroman'
]);
$mpdf = new \Mpdf\Mpdf($pdf->config());
$pdf->setWatermark($mpdf);
// $mpdf->showWatermarkText === true
// $mpdf->watermark_font === 'timenewroman'
```

See [`PDFTest`](../tests/Unit/PDFTest.php) for more tested