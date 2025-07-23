<?php

namespace Turbotech\PDFTemplate\PDFs;
use Mockery;
use Mpdf\Mpdf;
use Turbotech\PDFTemplate\PDFs\PDF;
use Turbotech\PDFTemplate\Tests\TestCase;

class PDFTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Optionally, mock View facade if needed
    }

    public function test_config_returns_default_values()
    {
        $pdf = new PDF();
        $config = $pdf->config();

        $this->assertIsArray($config);
        $this->assertEquals('P', $config['orientation']); // PDF.php default is 'P'
        $this->assertEquals('A4', $config['format']);
        $this->assertEquals('georgia', $config['default_font']);
        $this->assertStringContainsString('temp/pdf', $config['tempDir']);
        $this->assertEquals('+aCJK', $config['mode']);
        $this->assertTrue($config['autoScriptToLang']);
        $this->assertFalse($config['autoLangToFont']);
        $this->assertEquals(100, $config['padding_header']);
        $this->assertEquals(28.346456693, $config['margin_top']);
        $this->assertEquals(15, $config['margin_left']);
        $this->assertEquals(15, $config['margin_right']);
    }

    public function test_config_can_override_values()
    {
        $pdf = new PDF(['orientation' => 'L', 'format' => 'A5', 'default_font' => 'timenewroman']);
        $config = $pdf->config();

        $this->assertEquals('L', $config['orientation']);
        $this->assertEquals('A5', $config['format']);
        $this->assertEquals('timenewroman', $config['default_font']);
    }

    public function test_config_includes_custom_fonts()
    {
        $pdf = new PDF();
        $config = $pdf->config();
        $fontData = $config['fontdata'];

        $this->assertArrayHasKey('khmerosmoullight', $fontData);
        $this->assertArrayHasKey('khmeroscontent', $fontData);
        $this->assertArrayHasKey('content', $fontData);
        $this->assertArrayHasKey('timenewroman', $fontData);
        $this->assertArrayHasKey('ttstandinvoice', $fontData);
    }

    public function test_getFileTempDir_returns_default_or_custom()
    {
        $pdf = new PDF();
        $this->assertStringContainsString('temp/pdf', $pdf->getFileTempDir());

        $custom = new PDF(['tempDir' => '/custom/temp']);
        $this->assertEquals('/custom/temp', $custom->getFileTempDir());
    }

    public function test_getFontDir_returns_default_or_custom()
    {
        $pdf = new PDF();
        $dirs = $pdf->getFontDir();
        $this->assertIsArray($dirs);
        $this->assertStringContainsString('fonts', $dirs[0]);

        $custom = new PDF(['fontDir' => '/custom/fonts']);
        $dirsCustom = $custom->getFontDir();
        $this->assertEquals(['/custom/fonts'], $dirsCustom);
    }

    public function test_getImageDir_returns_default_or_custom()
    {
        $pdf = new PDF();
        $this->assertStringContainsString('images', $pdf->getImageDir());

        $custom = new PDF(['imageDir' => '/custom/images']);
        $this->assertEquals('/custom/images', $custom->getImageDir());
    }

    public function test_getViewsDir_returns_default_or_custom()
    {
        $pdf = new PDF();
        $this->assertStringContainsString('views', $pdf->getViewsDir());

        $custom = new PDF(['views' => '/custom/views']);
        $this->assertEquals('/custom/views', $custom->getViewsDir());
    }

    public function test_getViewTemplatesDir_and_getViewTemplateByName()
    {
        $pdf = new PDF();
        $name = 'invoice';
        $expected = $pdf->getViewTemplatesDir() . 'invoice.blade.php';
        $this->assertEquals($expected, $pdf->getViewTemplateByName($name));
    }

    public function test_getViewExamplesDir_and_getViewExamplesByName()
    {
        $pdf = new PDF();
        $name = 'sample';
        $expected = $pdf->getViewExamplesDir() . 'sample.blade.php';
        $this->assertEquals($expected, $pdf->getViewExamplesByName($name));
    }

    public function test_logo_returns_default_or_custom()
    {
        $pdf = new PDF();
        $this->assertStringContainsString('logo.png', $pdf->logo());

        $custom = new PDF(['logo' => '/custom/logo.png']);
        $this->assertEquals('/custom/logo.png', $custom->logo());
    }

    public function test_renderHeaderHTML_returns_string()
    {
        $pdf = new PDF(['header' => '<header>Test</header>']);
        $result = $pdf->renderHeaderHTML();
        $this->assertIsString($result);
        $this->assertStringContainsString('Test', $result);
    }

    public function test_renderBodyHTML_returns_string()
    {
        $pdf = new PDF(['body' => '<body>Test</body>']);
        $result = $pdf->renderBodyHTML();
        $this->assertIsString($result);
        $this->assertStringContainsString('Test', $result);
    }

    public function test_renderFooterHTML_returns_string()
    {
        $pdf = new PDF(['footer' => '<footer>Test</footer>']);
        $result = $pdf->renderFooterHTML();
        $this->assertIsString($result);
        $this->assertStringContainsString('Test', $result);
    }

    public function test_setWatermark_sets_properties_on_mpdf()
    {
        $pdf = new PDF([
            'showWatermark' => true,
            'watermarkText' => 'CONFIDENTIAL',
            'watermarkTextAlpha' => 0.5,
            'watermarkFontFamily' => 'timenewroman'
        ]);
        $mpdfMock = $this->getMockBuilder(Mpdf::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['SetWatermarkText'])
            ->getMock();

        $mpdfMock->expects($this->once())
            ->method('SetWatermarkText')
            ->with('CONFIDENTIAL', 0.5);

        $pdf->setWatermark($mpdfMock);

        $this->assertTrue($mpdfMock->showWatermarkText);
        $this->assertEquals('timenewroman', $mpdfMock->watermark_font);
    }
}