## Template Class Test Case Examples (Old version)

### Basic Configuration

```php
use Turbotech\PDFTemplate\Controllers\Template;

// Get default config
$config = Template::config();
print_r($config);
// Output: orientation = 'L', format = 'A4', default_font = 'georgia'

// Override config values
$config = Template::config([
    'orientation' => 'P',
    'format' => 'A5',
    'default_font' => 'timenewroman'
]);
print_r($config);
// Output: orientation = 'P', format = 'A5', default_font = 'timenewroman'
```

### Font Data

```php
$config = Template::config();
$fontData = $config['fontdata'];
var_dump(array_keys($fontData));
// Output includes: 'khmerosmoullight', 'khmeroscontent', 'content', 'timenewroman', 'ttstandinvoice'
```

### Config Array Structure

```php
$config = Template::config();
var_dump(array_keys($config));
// Output includes: 'mode', 'format', 'fontdata', etc.
```

### Merging Custom Config Values

```php
$config = Template::config([
    'margin_top' => 50,
    'format' => 'A5'
]);
echo $config['margin_top']; // 50
echo $config['format'];     // 'A5'
```

### Supported Fonts

```php
$config = Template::config();
$fontData = $config['fontdata'];
// Check for required fonts
foreach (['khmerosmoullight', 'khmeroscontent', 'content', 'timenewroman', 'ttstandinvoice'] as $font) {
    assert(array_key_exists($font, $fontData));
}
```

### Temp Directory

```php
$config = Template::config();
echo $config['tempDir']; // Contains 'temp/pdf'
```

See [`TemplateTest`](../tests/Unit/TemplateTest.php) for more tested scenarios