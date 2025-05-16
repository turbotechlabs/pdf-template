<?php

namespace Turbotech\PDFTemplate\Tests\Unit;

use Turbotech\Facades\PDFTemplate;
use Turbotech\PDFTemplate\Facades\PDFTemplate as FacadesPDFTemplate;
use Turbotech\PDFTemplate\Tests\TestCase;

class FacadeTest extends TestCase
{
    /** @test */
    public function it_returns_the_correct_facade_accessor()
    {
        $reflection = new \ReflectionClass(FacadesPDFTemplate::class);
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);

        $this->assertEquals('pdf-template', $method->invoke(null));
    }
}
