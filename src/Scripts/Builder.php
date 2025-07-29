<?php

namespace Turbotech\PDFTemplate\Scripts;

class Builder
{
    /**
     * Check arguments and display help message.
     *
     * @return void
     */
    public static function checkArguments(): void
    {
        global $argc, $argv;
        if ($argc < 2 || in_array('--help', $argv) || in_array('-h', $argv)) {
            self::display();
            exit(0);
        }
    }

    /**
     * Display help message for the PDF template commands.
     */
    public static function display(): void
    {
        echo "\n\033[31mUsage: composer make-pdf PDFName [options]\033[0m\n\n";
        echo "\033[42;37mFlags:\033[0m\n\n";
        echo "\033[90m  --help, -h\033[0m                     Show this help message\n";
        echo "\033[90m  --views, --with-views, -wv\033[0m     Create PDF with view templates (make-pdf only)\n";
        echo "\n\033[42;37mAliases:\033[0m\n\n";
        echo "🚧\033[32m  composer make-pdf <PDFName>\033[0m                Create a new PDF\n";
        echo "🚧\033[32m  composer make-controller <ControllerName>\033[0m  Create a new controller\n";
    }

    /**
     * Replacement for stub placeholders.
     *
     * @param string $stub
     * @param string $name
     * @param string $namespace
     * @return string
     */
    public static function replacement(string $stub, string $name, string $namespace): string
    {
        $stub = str_replace('{_name_}', $name, $stub);
        $stub = str_replace('${name_lower}$', strtolower($name), $stub);
        $stub = str_replace('{_namespace_}', $namespace, $stub);
        return $stub;
    }

    /**
     * Create a PDF template with the given name and namespace.
     *
     * @param string $name
     * @param string $namespace
     * @return string
     */
    public static function createPDFTemplate(string $name, string $namespace): string
    {
        if (empty($name)) {
            echo "\n\033[31mPDF name cannot be empty.\033[0m\n";
            exit(0);
        }

        // Ensure the name is a valid PHP class name
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {
            echo "\n\033[31mInvalid PDF name: {$name}. Must be a valid PHP class name.\033[0m\n";
            exit(0);
        }

        // Ensure the namespace is valid
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_\\\\]*$/', $namespace)) {
            echo "\n\033[31mInvalid namespace: {$namespace}.\033[0m\n";
            exit(0);
        }

        // get PDF from stub
        if (empty($stub = file_get_contents(__DIR__ . '/../Stubs/PDF.stub'))) {
            echo "\n\033[31mFailed to load PDF stub.\033[0m\n";
            exit(0);
        }

        return self::replacement($stub, $name, $namespace);
    }

    /**
     * Create view templates for the PDF.
     * 1. Create header template
     * 2. Create body template
     * 3. Create footer template
     */
    public static function createViewTemplates(string $name): array
    {
        $headerTemplate = __DIR__ . '/../views/templates/headers/' . $name . '.blade.php';
        $bodyTemplate = __DIR__ . '/../views/templates/bodies/' . $name . '.blade.php';
        $footerTemplate = __DIR__ . '/../views/templates/footers/' . $name . '.blade.php';

        if (!is_dir(dirname($headerTemplate))) {
            mkdir(dirname($headerTemplate), 0777, true);
        }
        if (!is_dir(dirname($bodyTemplate))) {
            mkdir(dirname($bodyTemplate), 0777, true);
        }
        if (!is_dir(dirname($footerTemplate))) {
            mkdir(dirname($footerTemplate), 0777, true);
        }

        return [
            'header' => $headerTemplate,
            'body' => $bodyTemplate,
            'footer' => $footerTemplate
        ];
    }

    /**
     * Create the PDF file with the given name and namespace.
     *
     * @param string $name
     * @param string $namespace
     * @return string
     */
    public static function createPDFFile(string $name, string $namespace): string
    {
        $dir = __DIR__ . '/../PDFs/';
        $file = $dir . $name . '.php';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($file)) {
            echo "\n\033[31m❌ PDF already exists\033[0m\n\033[31m📁 {$file}\033[0m\n";
            exit(0);
        }

        $content = self::createPDFTemplate($name, $namespace);
        file_put_contents($file, $content);
        echo "\n\033[32m✅ PDF created successfully\033[0m\n\033[32m📁 {$file}\033[0m\n";

        return $file;
    }
    /**
     * Create the controller file with the given name and namespace.
     *
     * @param string $name
     * @param string $namespace
     * @return string
     */
    public static function createControllerFile(string $name, string $namespace): string
    {
        $dir = __DIR__ . '/../Controllers/';
        $file = $dir . $name . '.php';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($file)) {
            echo "\n\033[31m❌ Controller already exists\033[0m\n\033[31m📁 {$file}\033[0m\n";
            exit(0);
        }

        $content = self::replacement(file_get_contents(__DIR__ . '/../Stubs/Controller.stub'), $name, $namespace);
        file_put_contents($file, $content);
        echo "\n\033[32m✅ Controller created successfully\033[0m\n\033[32m📁 {$file}\033[0m\n";

        return $file;
    }
    /**
     * Create the PDF and its associated view templates.
     *
     * @param string $name
     * @param string $namespace
     * @return void
     */
    public static function createPDFWithViews(string $name, string $namespace): void
    {
        $pdfFile = self::createPDFFile($name, $namespace);
        $viewTemplates = self::createViewTemplates($name);

        file_put_contents($viewTemplates['header'], "<h1>Header for {$name}</h1>");
        file_put_contents($viewTemplates['body'], "<p>Body content for {$name}</p>");
        file_put_contents($viewTemplates['footer'], "<p>Footer for {$name}</p>");

        echo "\n\033[32m✅ View templates created successfully\033[0m\n";
        echo "\033[32m📁 Header: {$viewTemplates['header']}\033[0m\n";
        echo "\033[32m📁 Body: {$viewTemplates['body']}\033[0m\n";
        echo "\033[32m📁 Footer: {$viewTemplates['footer']}\033[0m\n";
        echo "\n\033[32m✅ PDF and view templates created successfully\033[0m\n";
        echo "\033[32m📁 PDF: {$pdfFile}\033[0m\n";
    }
}
