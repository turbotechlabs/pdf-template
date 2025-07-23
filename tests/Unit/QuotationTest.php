<?php
namespace Turbotech\PDFTemplate\PDFs;

use Turbotech\PDFTemplate\PDFs\Quotation;
use Turbotech\PDFTemplate\Tests\TestCase;

class QuotationTest extends TestCase
{
    public function test_can_be_instantiated_with_defaults()
    {
        $quotation = new Quotation();

        $this->assertInstanceOf(Quotation::class, $quotation);
        $this->assertEquals('quotation', $quotation->headerTemplate);
        $this->assertEquals('example', $quotation->bodyTemplate);
        $this->assertEquals('quotation', $quotation->footerTemplate);

        $this->assertEquals('quotation', $quotation->options->headerTemplate);
        $this->assertEquals('example', $quotation->options->bodyTemplate);
        $this->assertEquals('quotation', $quotation->options->footerTemplate);
    }

    public function test_can_override_options()
    {
        $options = [
            'headerTemplate' => 'customHeader',
            'bodyTemplate' => 'customBody',
            'footerTemplate' => 'customFooter',
            'customOption' => 'customValue'
        ];
        $quotation = new Quotation($options);

        // The class properties remain default, but options are overridden
        $this->assertEquals('quotation', $quotation->headerTemplate);
        $this->assertEquals('example', $quotation->bodyTemplate);
        $this->assertEquals('quotation', $quotation->footerTemplate);

        $this->assertEquals('customHeader', $quotation->options->headerTemplate);
        $this->assertEquals('customBody', $quotation->options->bodyTemplate);
        $this->assertEquals('customFooter', $quotation->options->footerTemplate);
        $this->assertEquals('customValue', $quotation->options->customOption);
    }

    public function test_options_are_passed_to_parent_constructor()
    {
        $options = ['foo' => 'bar'];
        $quotation = new Quotation($options);

        $this->assertObjectHasAttribute('foo', $quotation->options);
        $this->assertEquals('bar', $quotation->options->foo);
    }
}