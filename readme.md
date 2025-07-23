![](./src//images/pre.png)

[![PHP Tests](https://github.com/turbotechlabs/pdf-template/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/turbotechlabs/pdf-template/actions/workflows/run-tests.yml)

# PDF Template Package

A Laravel package for generating PDF documents using mPDF with Khmer font support and custom templates.

## Project Setup

### Prerequisites

Before running tests or using the package, ensure you have:

1. Installed all dependencies:
   ```bash
   composer install
   ```

2. Created the required temporary directory for mPDF:
   ```bash
   mkdir -p temp/pdf
   ```

### Running Tests

You can run the test suite using any of these methods:

- Using Composer script (recommended):
  ```bash
  composer test
  ```

- Running PHPUnit directly:
  ```bash
  vendor/bin/phpunit
  ```

- Running specific test suite:
  ```bash
  vendor/bin/phpunit --testsuite=Unit
  ```

- Running a specific test file:
  ```bash
  vendor/bin/phpunit tests/Unit/TemplateTest.php
  ```

- Running a specific test method:
  ```bash
  vendor/bin/phpunit --filter=it_can_get_config_with_default_values
  ```

### Test Coverage Report

To generate an HTML test coverage report, run:

```bash
composer test-coverage
```

This will create HTML reports in the `coverage` directory.

### Continuous Integration

GitHub Actions workflow in [`.github/workflows/run-tests.yml`](.github/workflows/run-tests.yml) automatically runs tests across multiple PHP and Laravel versions on push or pull request.

### Extending Tests

When adding new features, please add corresponding tests in:

- Unit tests: `tests/Unit/`

## Installation

You can install the PDF Template package via [Packagist](https://packagist.org/packages/smarterp-dev/pdf-template):

### 1. Require the package via Composer

```bash
composer require smarterp-dev/pdf-template
```

### 2. Publish the package views (optional)

If you want to customize the PDF templates, publish the views to your Laravel application's resources directory:

```bash
php artisan vendor:publish --tag=pdf-template-views
```

This will copy the package views to `resources/views/vendor/pdf-template`.

### 3. Create the required temporary directory for mPDF

```bash
mkdir -p temp/pdf
```

### 4. Configuration

The package will automatically register its service provider. No manual configuration is required for basic usage.

### 5. Usage

Refer to [docs/pdf.md](docs/pdf.md) and [docs/template.md](docs/template.md) for usage examples and API documentation.


## License

MIT License. See [LICENSE](LICENSE)