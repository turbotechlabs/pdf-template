<?php

namespace Turbotech\PDFTemplate\PDFs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PDF
{
    protected Request $request;
    protected object $options;

    public function __construct(array $options = [])
    {
        $this->request = new Request();
        $this->options = (object) array_merge(
            ["orientation" => $this->request->get('o', $this->request->get('orientation', 'P')),],
            $this->request->all(),
            $options,
        );
    }

    /**
     * Get the configuration for the PDF.
     *
     * @param mixed ...$arg
     * @return array
     */
    public function config(...$arg): array
    {
        $options = $this->options;
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirectory = array_merge(
            $defaultConfig['fontDir'],
            $this->getFontDir()
        );
        $defaultFontConfig  = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $configs = [
            'mode' => '+aCJK',
            'orientation' => $options->orientation ?? 'L',
            'page_size' => $options->page_size ?? 'A4',
            'format' => $options->format ?? 'A4',
            'autoScriptToLang' => true,
            'autoLangToFont' => false,
            'tempDir' => $this->getFileTempDir(),
            'fontDir' => $fontDirectory,
            'fontdata' => $fontData + [
                'khmerosmoullight' => [
                    'R' => 'Khmer-OS-Muol-Light.ttf',
                    'useOTL' => 0xFF,
                ],
                'khmeroscontent' => [
                    'R' => 'KhmerOS_content.ttf',
                    'useOTL' => 0xFF,
                ],
                'content' => [
                    'R' => 'Content-Regular.ttf',
                    'B' => 'Content-Bold.ttf',
                    'useOTL' => 0xFF,
                ],
                'timenewroman' => [
                    'R' => 'times_new_roman.ttf',
                    'B' => 'times_new_roman_bold.ttf',
                    'I' => 'times_new_roman_italic.ttf',
                    'BI' => 'times_new_roman_bold_italic.ttf',
                    'useOTL' => 0xFF,
                ],
                'ttstandinvoice' => [
                    'R' => 'Content-TimesNewRoman.otf',
                    'useOTL' => 0xFF
                ],
            ],
            'default_font' => $options->default_font ?? 'georgia',
            'padding_header' => 100,
            'margin_top' => 28.346456693,
            'margin_left' => 15,
            'margin_right' => 15
        ];

        // Merge all arguments as overrides
        $overrides = (object)[];
        foreach ($arg as $a) {
            if (is_array($a)) {
                $overrides = (object)array_merge((array)$overrides, $a);
            }
        }
        $data = array_replace(
            $configs,
            (array)$options,
            ["orientation" => strtoupper($overrides->o ?? 'P')],
            (array)$overrides
        );

        $this->options = (object) $data;
        return $data;
    }

    /**
     * Get the temporary directory for PDF files.
     * @param options->tempDir
     * @return string
     */
    public function getFileTempDir(): string
    {
        return $this->options->tempDir ?? __DIR__ . '/../../temp/pdf';
    }

    /**
     * Get the font directory.
     * @param options->fontDir
     * @return array
     */
    public function getFontDir(): array
    {
        return [$this->options->fontDir ?? __DIR__ . '/../fonts/'];
    }

    /**
     * Get the image directory.
     * @param options->imageDir
     * @return string
     */
    public function getImageDir(): string
    {
        return $this->options->imageDir ?? __DIR__ . '/../images/';
    }

    /**
     * Get the views directory.
     * @param options->views
     * @return string
     */
    public function getViewsDir(): string
    {
        return $this->options->views ?? __DIR__ . '/../views/';
    }

    /**
     * Get the view templates directory.
     * @param options->viewTemplates
     * @return string
     */
    public function getViewTemplatesDir(): string
    {
        return $this->options->viewTemplates ?? __DIR__ . '/../views/templates/';
    }

    /**
     * Get the view template by name.
     * @param string $name
     * @return string
     */
    public function getViewTemplateByName(string $name): string
    {
        $name = strtolower($name);
        return "{$this->getViewTemplatesDir()}{$name}.blade.php";
    }

    /**
     * Get the view examples directory.
     * @param options->viewExamples
     * @return string
     */
    public function getViewExamplesDir(): string
    {
        return $this->options->viewExamples ?? __DIR__ . '/../views/example/';
    }

    /**
     * Get the view examples by name.
     * @param string $name
     * @return string
     */
    public function getViewExamplesByName(string $name): string
    {
        $name = strtolower($name);
        return "{$this->getViewExamplesDir()}{$name}.blade.php";
    }


    /**
     * Get logo path.
     *
     * @param options->logo
     * @return string
     */
    public function logo(): string
    {
        return $this->options->logo ?? __DIR__ . '/../images/logo.png';
    }

    /**
     * Get the header view file.
     * @uses View $view
     * @param options->viewHeader
     * @return string
     */
    public function getViewHeader(): string
    {
        return $this->options->viewHeader ?? __DIR__ . '/../views/header.blade.php';
    }

    /**
     * Get the default header template.
     * @param options->header
     * @return string
     */
    public function getDefaultHeaderTemplate(): string
    {
        return $this->options->header ?? $this->getViewExamplesDir() . 'header.blade.php';
    }

    /**
     * Render the header for the PDF.
     * @uses Mpdf $mpdf
     *
     * @param options->header
     * @param options->logo
     * @param options->companyName
     * @param options->headerTitle
     * @param options->headerSubtitle
     * @param options->headerTemplate
     * @return string|null
     */
    public function renderHeaderHTML(): ?string
    {
        if (isset($this->options->header)) {
            return $this->options->header;
        }

        $imagePath = isset($this->options->logo) && file_exists($this->options->logo)
            ? $this->options->logo
            : $this->logo();

        $params = [
            'header' => [
                'company' => $this->options->companyName ?? 'TURBOTECH CO., LTD.',
                'title' => $this->options->headerTitle ?? 'Quotation',
                'subtitle' => $this->options->headerSubtitle ?? 'Generated by Turbotech PDF Template',
                'orientation' => $this->options->orientation ?? 'L',
            ],
            'headerImage' => $imagePath,
        ];

        $template = "templates/headers/example";
        if (
            isset($this->options->headerTemplate)
            && file_exists($this->getViewTemplateByName("headers/{$this->options->headerTemplate}"))
        ) {
            $template = "templates/headers/{$this->options->headerTemplate}";
        }
        $data = array_merge($params, (array)$this->options ?? []);
        return $this->view($template, compact('data'));
    }


    /**
     * Render the body for the PDF.
     * @param options->body
     * @param options->bodyTemplate
     * @return string|null
     */
    public function renderBodyHTML(): ?string
    {
        if (isset($this->options->body)) {
            return $this->options->body;
        }

        $template = "templates/bodies/example";
        if (isset($this->options->bodyTemplate) && file_exists($this->getViewTemplateByName("bodies/{$this->options->bodyTemplate}"))) {
            $template = "templates/bodies/{$this->options->bodyTemplate}";
        }
        $data = $this->options ?? [];
        return $this->view($template, compact('data'));
    }


    /**
     * Render the footer for the PDF.
     * @uses Mpdf $mpdf
     * @param options->footer
     * @param options->footerTemplate
     * @return void
     */
    public function renderFooter($mpdf): void
    {
        if (isset($this->options->footer)) {
            $mpdf->SetHTMLFooter($this->options->footer);
        }

        $params = [
            'footer' => [ 'text' => $this->options->footerText ?? 'Generated by Turbotech PDF Template', ],
        ];

        $template = 'templates/footers/example';
        if (
            isset($this->options->footerTemplate)
            && file_exists($this->getViewTemplateByName("footers/{$this->options->footerTemplate}"))
        ) {
            $template = "templates/footers/{$this->options->footerTemplate}";
        }

        $data = (object)array_merge($params, (array)$this->options ?? []);

        $mpdf->SetHTMLFooter(
            $this->view($template, compact('data'))
        );
    }


    /**
     * Render the footer HTML.
     *
     * @uses View $view
     * @param options->footer
     * @param options->footerTemplate
     * @param options->footerText
     * @return string
     */
    public function renderFooterHTML(): string
    {
        if (isset($this->options->footer)) {
            return $this->options->footer;
        }

        $template = 'templates/footers/example';
        if (
            isset($this->options->footerTemplate)
            && file_exists($this->getViewTemplateByName("footers/{$this->options->footerTemplate}"))
        ) {
            $template = "templates/footers/{$this->options->footerTemplate}";
        }
        $params = [
            'footer' => [
                'text' => $this->options->footerText ?? 'Generated by Turbotech PDF Template',
            ],
        ];

        $data = (object)array_merge($params, (array)$this->options ?? []);
        return $this->view($template, compact('data'));
    }

    /**
     * Set watermark text on PDF document.
     * @uses Mpdf $mpdf
     *
     * @param options->showWatermark
     * @param options->watermarkText
     * @param options->watermarkTextAlpha
     * @param options->watermarkFontFamily
     * @return void
     */
    public function setWatermark($mpdf): void
    {
        $showWatermarkText = (bool)$this->options->showWatermark ?? false;
        $markerText = $this->options->watermarkText ?? '';
        $markerTextAlpha = $this->options->watermarkTextAlpha ?? 0.1;
        $fontFamily = $this->options->watermarkFontFamily ?? 'khmerosmoullight';

        $mpdf->showWatermarkText = $showWatermarkText;
        $mpdf->SetWatermarkText($markerText, $markerTextAlpha);
        $mpdf->watermark_font = $fontFamily;
    }

    /**
     * Render view file.
     * @uses View $view
     *
     * @param string $viewName
     * @param array $data
     */
    public function view(string $viewName, array $data = []): string
    {
        $viewName = str_replace('.blade.php', '', $viewName);
        return View::file("{$this->getViewsDir()}{$viewName}.blade.php", $data)->render();
    }

    /**
     * render the PDF document.
     * @uses Mpdf $mpdf
     */
    public function render(): void
    {
        $options = $this->options;
        $rows = $options->rows ?? 0; // Get the number of rows from options
        $orientation = strtoupper($options->orientation ?? "P"); // Default to Portrait
        $isLandscape = $orientation === "L";
        $rowLimit = $isLandscape
            ? ($options->landscapeRowsLimit ?? 16) // Landscape default 16 rows
            : ($options->portraitRowsLimit ?? 28); // Portrait default 28 rows

        $config = $this->config([
            // Configure margin_bottom for Landscape and Portrait
            'margin_bottom' => $rows < $rowLimit ? ($isLandscape ? 54 : 10) : 14,
            'orientation' => $orientation,
        ]);

        $pdfTitle = ($config['title'] ?? 'PDF') . "_" . date($options->titleDateFormat ?? 'dmY_His');

        $mpdf = new Mpdf($config);
        $mpdf->SetTitle($pdfTitle);
        $mpdf->SetAuthor($options->author ?? 'TurboTech PDF Template');
        $mpdf->SetCreator($options->creator ?? 'SmartERP');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetProtection(['copy', 'print'], '', 'pass');
        $mpdf->SetCompression(true);

        $header = $this->renderHeaderHTML();
        $body = $this->renderBodyHTML();

        $footer = $rows >= $rowLimit
            ? $this->renderFooterHTML()
            : '';

        if ($rows < $rowLimit) {
            $this->renderFooter($mpdf);
        }

        $mpdf->WriteHTML(
            "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>{$pdfTitle}</title>
                <style>
                    .rounded-box {
                        width: 100%;
                        height: auto;
                        line-height: 1.5rem;
                        text-align: center;
                        border-radius: 30px;
                        border: 1px solid #1fa8e0;
                    }
                </style>
            </head>
            <body>
                {$header} {$body} {$footer}
            </body>
            </html>"
        );

        $mpdf->Output($pdfTitle . '.pdf', 'I');
    }
}
