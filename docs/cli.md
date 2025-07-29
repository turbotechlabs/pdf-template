# PDF Template Package CLI Usage

This package provides CLI tools for generating PDF classes and controllers, as well as their associated Blade view templates. These tools help you quickly scaffold new PDF templates and controllers for your Laravel application.

## Available CLI Commands

### 1. Make a PDF Class

Creates a new PDF class in `src/PDFs/` and generates corresponding Blade view templates in `src/views/templates/headers/`, `bodies/`, and `footers/`.

**Usage:**
```sh
composer make-pdf PDFName --[flags]
```
>    **Flags:**
>   `--views`, `--with-views`, `-wv`      Create PDF with view templates`



**Example:**
```sh
composer make-pdf Invoice
```

**What it does:**
- Creates `src/PDFs/Invoice.php`
- Creates if set options  `--views`, `--with-views`, `-wv`:
  - `src/views/templates/headers/Invoice.blade.php`
  - `src/views/templates/bodies/Invoice.blade.php`
  - `src/views/templates/footers/Invoice.blade.php`

If the PDF class already exists, the command will abort and show an error.

---

### 2. Make a Controller

Creates a new controller class in `src/Controllers/`.

**Usage:**
```sh
composer make-controller ControllerName
```

**Example:**
```sh
composer make-controller ReportController
```

**What it does:**
- Creates `src/Controllers/ReportController.php` with a basic class stub.

If the controller already exists, the command will abort and show an error.

---

## How It Works

These commands are implemented as PHP scripts in `src/Scripts/MakePDF.php` and `src/Scripts/MakeController.php`. They are registered as Composer scripts in [composer.json](../composer.json):

```json
"scripts": {
    "make-pdf": "php src/Scripts/MakePDF.php",
    "make-controller": "php src/Scripts/MakeController.php"
}
```

When you run `composer make-pdf` or `composer make-controller`, Composer executes the corresponding PHP script.

---

## Customizing the Generated Files

- The generated PDF and controller classes use stubs located in `src/Stubs/`.
- You can modify these stubs to change the default structure of generated files.

---

## Troubleshooting

- **Missing Arguments:** If you do not provide a name, the script will show usage instructions.
- **Invalid Names:** Names must be valid PHP class names (letters, numbers, underscores, starting with a letter or underscore).
- **File Already Exists:** The script will not overwrite existing files.

---

## Example Workflow

1. **Generate a new PDF class and templates:**
    ```sh
    composer make-pdf Quotation
    ```
    - Edit `src/PDFs/Quotation.php` and the generated Blade templates as needed.

2. **Generate a new controller:**
    ```sh
    composer make-controller QuotationController
    ```
    - Edit `src/Controllers/QuotationController.php` as needed.

---

## See Also

- [PDF Class Usage](pdf.md)