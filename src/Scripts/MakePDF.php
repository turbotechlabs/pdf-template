<?php

namespace Turbotech\PDFTemplate\Commands;

class MakePDF
{
    protected string $name;
    protected string $namespace;
    protected string $dir;
    protected string $file;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->namespace = 'Turbotech\\PDFTemplate\\PDFs';
        $this->dir = __DIR__ . '/../PDFs/';
        $this->file = $this->dir . $this->name . '.php';
    }

    protected function template(): string
    {
        if (empty($this->name)) {
            echo "\n\033[31mPDF name cannot be empty.\033[0m\n";
            exit(0);
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $this->name)) {
            echo "\n\033[31mInvalid PDF name: {$this->name}. Must be a valid PHP class name.\033[0m\n";
            exit(0);
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_\\\\]*$/', $this->namespace)) {
            echo "\n\033[31mInvalid namespace: {$this->namespace}.\033[0m\n";
            exit(0);
        }

        $stub = file_get_contents(__DIR__ . '/../Stubs/PDF.stub');
        $stub = str_replace('${name}$', $this->name, $stub);
        $stub = str_replace('${name_lower}$', strtolower($this->name), $stub);
        $stub = str_replace('${namespace}$', $this->namespace, $stub);

        return $stub;
    }

    /**
     * Create view templates for the PDF.
     * 1. Create header template
     * 2. Create body template
     * 3. Create footer template
    */
    public function createViewTemplates(): string
    {
        $headerTemplate = __DIR__ . '/../views/templates/headers/' . $this->name . '.php';
        $bodyTemplate = __DIR__ . '/../views/templates/bodies/' . $this->name . '.php';
        $footerTemplate = __DIR__ . '/../views/templates/footers/' . $this->name . '.php';

        if (!is_dir(dirname($headerTemplate))) {
            mkdir(dirname($headerTemplate), 0777, true);
        }
        $headerTemplate = __DIR__ . '/../views/templates/headers/' . $this->name . '.blade.php';
        $bodyTemplate = __DIR__ . '/../views/templates/bodies/' . $this->name . '.blade.php';
        $footerTemplate = __DIR__ . '/../views/templates/footers/' . $this->name . '.blade.php';

        if (!is_dir(dirname($headerTemplate))) {
            mkdir(dirname($headerTemplate), 0777, true);
        }
        if (!is_dir(dirname($bodyTemplate))) {
            mkdir(dirname($bodyTemplate), 0777, true);
        }
        if (!is_dir(dirname($footerTemplate))) {
            mkdir(dirname($footerTemplate), 0777, true);
        }

        file_put_contents($headerTemplate, "<h1>Header for {$this->name}</h1>");
        file_put_contents($bodyTemplate, "<p>Body content for {$this->name}</p>");
        file_put_contents($footerTemplate, "<p>Footer for {$this->name}</p>");

        return "View templates created successfully.";
    }


    public function render(): void
    {
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }

        if (file_exists($this->file)) {
            echo "\n\033[31m❌ PDF already exists\033[0m\n\033[31m📁 {$this->file}\033[0m\n";
            exit(0);
        }

        // Create pdf controller file
        file_put_contents($this->file, $this->template());
        $this->createViewTemplates();
        echo "\n\033[32m✅ PDF created successfully\033[0m\n\033[32m📁 {$this->file}\033[0m\n";
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    global $argc, $argv;
    if ($argc < 2) {
        echo "Usage: composer make-pdf PDFName\n";
        exit(0);
    }
    $make = new MakePDF($argv[1]);
    $make->render();
}
