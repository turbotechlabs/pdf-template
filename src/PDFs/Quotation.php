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
        $defaultOptions = [
            'hasImage' => false,
            'headerTemplate' => $this->headerTemplate,
            'bodyTemplate' => $this->bodyTemplate,
            'footerTemplate' => $this->footerTemplate
        ];

        $mergedOptions = $this->deepMerge($defaultOptions, $options ?? []);
        $this->options = (object)$mergedOptions;

        parent::__construct($mergedOptions);
    }

    /**
     * Deep merge two arrays
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private function deepMerge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->deepMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
