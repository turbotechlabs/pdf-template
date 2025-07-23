<?php
namespace Turbotech\PDFTemplate\PDFs;

use Mpdf\Mpdf;
use Turbotech\PDFTemplate\PDFs\Invoice;
use Turbotech\PDFTemplate\Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function test_can_be_instantiated_with_defaults()
    {
        $invoice = new Invoice();

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('invoice', $invoice->headerTemplate);
        $this->assertEquals('invoice', $invoice->bodyTemplate);
        $this->assertEquals('invoice', $invoice->footerTemplate);

        $this->assertEquals('invoice', $invoice->options->headerTemplate);
        $this->assertEquals('invoice', $invoice->options->bodyTemplate);
        $this->assertEquals('invoice', $invoice->options->footerTemplate);
    }

    public function test_can_override_options()
    {
        $options = [
            'headerTemplate' => 'customHeader',
            'bodyTemplate' => 'customBody',
            'footerTemplate' => 'customFooter',
            'customOption' => 'customValue'
        ];
        $invoice = new Invoice($options);

        // The class properties remain default, but options are overridden
        $this->assertEquals('invoice', $invoice->headerTemplate);
        $this->assertEquals('invoice', $invoice->bodyTemplate);
        $this->assertEquals('invoice', $invoice->footerTemplate);

        $this->assertEquals('customHeader', $invoice->options->headerTemplate);
        $this->assertEquals('customBody', $invoice->options->bodyTemplate);
        $this->assertEquals('customFooter', $invoice->options->footerTemplate);
        $this->assertEquals('customValue', $invoice->options->customOption);
    }
}