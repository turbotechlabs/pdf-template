<?php

namespace Turbotech\PDFTemplate\PDFs;

class Quotation extends PDF
{
    public object $options;

    public string $headerTemplate = 'quotation';
    public string $bodyTemplate = 'example';
    public string $footerTemplate = 'quotation';

    /**
     * Quotation constructor.
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
