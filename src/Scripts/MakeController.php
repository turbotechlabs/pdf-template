<?php

namespace Turbotech\PDFTemplate\Scripts;
use Turbotech\PDFTemplate\Scripts\Builder;
class MakeController extends Builder
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

    /**
     * Render the controller file.
     *
     * @return void
     */
    public function render()
    {
        Builder::createControllerFile($this->name, $this->namespace);
    }
}


// CLI usage
if (php_sapi_name() === 'cli') {
    require_once __DIR__ . '/Builder.php';
    Builder::checkArguments();

    $make = new MakeController($argv[1]);
    $make->render();
}
