<?php

namespace Turbotech\PDFTemplate\Scripts;

use Turbotech\PDFTemplate\Scripts\Builder;

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

    /**
     * Render the PDF file.
     *
     * @return void
     */
    public function render()
    {
        Builder::createPDFFile($this->name, $this->namespace);
    }

    /**
     * Create a PDF with views.
     *
     * @return void
     */
    public function createPDFWithViews(): void
    {
        Builder::createPDFWithViews($this->name, $this->namespace);
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    require_once __DIR__ . '/Builder.php';
    Builder::checkArguments();

    $withViews = in_array('--views', $argv) || in_array('--with-views', $argv) || in_array('-wv', $argv);
    $make = new MakePDF($argv[1]);

    if ($withViews) {
        $make->createPDFWithViews();
    } else {
        $make->render();
    }
}
