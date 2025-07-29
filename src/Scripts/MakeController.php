<?php

namespace Turbotech\PDFTemplate\Commands;

class MakeController
{
    protected string $name;
    protected string $namespace;
    protected string $dir;
    protected string $file;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->namespace = 'Turbotech\\PDFTemplate\\Controllers';
        $this->dir = __DIR__ . '/../Controllers/';
        $this->file = $this->dir . $this->name . '.php';
    }

    protected function template(): string
    {
        if (empty($this->name)) {
            echo "\n\033[31mController name cannot be empty.\033[0m\n";
            exit(0);
        }

        // Ensure the name is a valid PHP class name
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $this->name)) {
            echo "\n\033[31mInvalid controller name: {$this->name}. Must be a valid PHP class name.\033[0m\n";
            exit(0);
        }

        // Ensure the namespace is valid
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_\\\\]*$/', $this->namespace)) {
            echo "\n\033[31mInvalid namespace: {$this->namespace}.\033[0m\n";
            exit(0);
        }

        // get Controller from stub
        $stub = file_get_contents(__DIR__ . '/../Stubs/Controller.stub');
        $stub = str_replace('${name}$', $this->name, $stub);
        $stub = str_replace('${namespace}$', $this->namespace, $stub);

        return $stub;
    }

    public function render(): void
    {
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }

        if (file_exists($this->file)) {
            echo "\n\033[31m❌ Controller already exists\033[0m\n\033[31m📁 {$this->file}\033[0m\n";
            exit(0);
        }

        file_put_contents($this->file, $this->template());
        echo "\n\033[32m✅ Controller created successfully\033[0m\n\033[32m📁 {$this->file}\033[0m\n";
    }
}


// CLI usage
if (php_sapi_name() === 'cli') {
    global $argc, $argv;
    if ($argc < 2) {
        echo "Usage: composer make-controller ControllerName\n";
        exit(0);
    }
    $make = new MakeController($argv[1]);
    $make->render();
}
