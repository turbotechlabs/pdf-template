<?php

namespace Turbotech\PDFTemplate\Tests\Unit;

use Illuminate\Http\Request;
use Mockery;
use Turbotech\PDFTemplate\Controllers\Template;
use Turbotech\PDFTemplate\Tests\TestCase;

class TemplateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_config_with_default_values()
    {
        $config = Template::config();

        $this->assertIsArray($config);
        $this->assertEquals('L', $config['orientation']);
        $this->assertEquals('A4', $config['format']);
        $this->assertEquals('georgia', $config['default_font']);
    }

    /** @test */
    public function it_can_override_config_values()
    {
        $config = Template::config([
            'orientation' => 'P',
            'format' => 'A5',
            'default_font' => 'timenewroman'
        ]);

        $this->assertIsArray($config);
        $this->assertEquals('P', $config['orientation']);
        $this->assertEquals('A5', $config['format']);
        $this->assertEquals('timenewroman', $config['default_font']);
    }

    /** @test */
    public function it_includes_custom_fonts_in_config()
    {
        $config = Template::config();

        $this->assertIsArray($config['fontdata']);
        $this->assertArrayHasKey('khmerosmoullight', $config['fontdata']);
        $this->assertArrayHasKey('khmeroscontent', $config['fontdata']);
        $this->assertArrayHasKey('content', $config['fontdata']);
        $this->assertArrayHasKey('timenewroman', $config['fontdata']);
    }

    /** @test */
    public function it_can_generate_config_array()
    {
        // Test the config method returns an array with expected keys
        $config = Template::config();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('mode', $config);
        $this->assertArrayHasKey('format', $config);
        $this->assertArrayHasKey('fontdata', $config);
    }

    /** @test */
    public function it_can_merge_custom_config_values()
    {
        // Test that custom configs are merged properly
        $customMargin = 50;
        $customFormat = 'A5';

        $config = Template::config([
            'margin_top' => $customMargin,
            'format' => $customFormat
        ]);

        $this->assertEquals($customMargin, $config['margin_top']);
        $this->assertEquals($customFormat, $config['format']);
    }

    /** @test */
    public function it_supports_all_required_fonts()
    {
        // Test that all fonts mentioned in the documentation are available
        $config = Template::config();
        $fontData = $config['fontdata'];

        $this->assertArrayHasKey('khmerosmoullight', $fontData);
        $this->assertArrayHasKey('khmeroscontent', $fontData);
        $this->assertArrayHasKey('content', $fontData);
        $this->assertArrayHasKey('timenewroman', $fontData);
        $this->assertArrayHasKey('ttstandinvoice', $fontData);
    }

    /** @test */
    public function it_sets_correct_temp_directory()
    {
        $config = Template::config();

        $this->assertStringContainsString('temp/pdf', $config['tempDir']);
    }
}
