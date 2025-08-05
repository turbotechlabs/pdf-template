# ISP Quotation Template Usage Guide

This guide explains how to use the ISP quotation template with the Turbotech PDF Template package.

## Overview

The ISP quotation template provides a professional quotation layout with:
- Company header with logo and bilingual text (Khmer/English)
- Customizable body content
- Signature footer with acknowledgment sections

## Basic Usage

```php
use Turbotech\PDFTemplate\PDFs\PDF;

$pdf = new PDF([
    "title" => "Quotation",
    "margin_top" => 16,
    "shrink_tables_to_fit" => 1,
    'headerTemplate' => "isp-quotation",
    "footerData" => (array)$data,
    "body" => view("selling::quotations.preview.isp", compact('data', 'terms')),
    'footerTemplate' => "isp-quotation",
]);

return $pdf->render();
```

## Configuration Options

### Required Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `headerTemplate` | string | Must be set to `"isp-quotation"` |
| `footerTemplate` | string | Must be set to `"isp-quotation"` |
| `body` | string | HTML content for the quotation body |

### Optional Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | string | "PDF" | Document title |
| `margin_top` | int | 28.346456693 | Top margin in points |
| `shrink_tables_to_fit` | int | 0 | Shrink tables to fit page width |
| `footerData` | array | [] | Data for footer acknowledgment section |
| `orientation` | string | "P" | Page orientation (P/L) |
| `page_size` | string | "A4" | Page size |

## Header Template Features

The ISP quotation header includes:
- Company logo (customizable via `headerImage` option)
- Bilingual company name (Khmer/English)
- VAT TIN number
- Complete address in both languages
- Contact information

### Header Configuration

```php
$pdf = new PDF([
    'headerTemplate' => "isp-quotation",
    'headerImage' => '/path/to/custom/logo.png', // Optional custom logo
    // ...other options
]);
```

## Footer Template Features

The footer provides signature sections for:
- Quote Accepted By
- Acknowledged By (conditional)
- Prepared By

### Footer Data Structure

The footer uses `footerData` to determine layout:

```php
$footerData = [
    'result' => [
        [
            'acknowledged_by' => 'John Doe',           // Optional
            'acknowledged_by_position' => 'Manager',   // Optional
        ]
    ]
];

$pdf = new PDF([
    'footerTemplate' => "isp-quotation",
    'footerData' => $footerData,
    // ...other options
]);
```

### Footer Behavior

- **Without `acknowledged_by`**: Shows 2 columns (Quote Accepted By, Prepared By)
- **With `acknowledged_by`**: Shows 3 columns (Quote Accepted By, Acknowledged By, Prepared By)

## Body Content

The body content is typically generated using a Blade view:

```php
$body = view("selling::quotations.preview.isp", compact('data', 'terms'));

$pdf = new PDF([
    'body' => $body,
    // ...other options
]);
```

## Complete Example

```php
<?php

use Turbotech\PDFTemplate\PDFs\PDF;

// Prepare quotation data
$quotationData = [
    'customer_name' => 'ABC Company',
    'quotation_number' => 'Q-2024-001',
    'items' => [
        ['description' => 'Web Development', 'quantity' => 1, 'price' => 1000],
        ['description' => 'Hosting Service', 'quantity' => 12, 'price' => 50],
    ],
    'total' => 1600,
];

// Footer data for acknowledgment
$footerData = [
    'result' => [
        [
            'acknowledged_by' => 'Jane Smith',
            'acknowledged_by_position' => 'Project Manager',
        ]
    ]
];

// Generate PDF
$pdf = new PDF([
    "title" => "ISP_Quotation",
    "orientation" => "P",
    "page_size" => "A4",
    "margin_top" => 16,
    "margin_bottom" => 10,
    "shrink_tables_to_fit" => 1,
    'headerTemplate' => "isp-quotation",
    'footerTemplate' => "isp-quotation",
    "footerData" => $footerData,
    "body" => view("quotations.isp-template", compact('quotationData')),
    "author" => "ISP Company",
    "creator" => "SmartERP System",
]);

// Output PDF
return $pdf->render();
```

## Template Files

The ISP quotation template consists of:

1. **Header**: `src/views/templates/headers/isp-quotation.blade.php`
2. **Footer**: `src/views/templates/footers/isp-quotation.blade.php`
3. **Body**: Custom view file (user-provided)

## Styling Notes

- The header uses custom fonts: `khmerosmoullight` and `khmeroscontent`
- Company colors use `#1fa8e1` blue theme
- Font sizes are optimized for A4 paper
- Responsive layout adapts to content length

## Troubleshooting

### Common Issues

1. **Missing Logo**: Ensure logo path is correct or use default
2. **Font Issues**: Verify Khmer fonts are available in font directory
3. **Layout Problems**: Check margin settings and content length
4. **Footer Data**: Ensure `footerData` structure matches expected format

### Debug Tips

```php
// Enable debug mode
$pdf = new PDF([
    'debug' => true,
    // ...other options
]);
```

## Advanced Customization

### Custom Logo

```php
$pdf = new PDF([
    'headerImage' => storage_path('app/logos/custom-logo.png'),
    // ...other options
]);
```

### Custom Margins

```php
$pdf = new PDF([
    'margin_top' => 20,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10,
    // ...other options
]);
```

### Landscape Orientation

```php
$pdf = new PDF([
    'orientation' => 'L',
    'landscapeRowsLimit' => 20, // Adjust for landscape
    // ...other options
]);
```
