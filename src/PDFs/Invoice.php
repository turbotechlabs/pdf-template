<?php

namespace Turbotech\PDFTemplate\PDFs;

class Invoice extends PDF
{
    public object $options;

    public string $headerTemplate = 'invoice';
    public string $bodyTemplate = 'invoice';
    public string $footerTemplate = 'invoice';

    /**
     * Invoice constructor.
     *
     * @param array $options
     */
    public function __construct(?array $options = [])
    {
        $this->options = (object)$options;
        $this->options->headerTemplate = $this->options->headerTemplate ?? $this->headerTemplate;
        $this->options->bodyTemplate = $this->options->bodyTemplate ?? $this->bodyTemplate;
        $this->options->footerTemplate = $this->options->footerTemplate ?? $this->footerTemplate;

        parent::__construct((array)$this->options);
    }
}
