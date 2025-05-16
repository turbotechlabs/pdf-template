<?php

namespace Turbotech\PDFTemplate\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class Template
{
    protected static $viewDir = __DIR__ . '/../views/example/';
    protected static $fontDir = __DIR__ . '/../fonts/';
    protected static $termDir = __DIR__ . '/../../temp/pdf';
    protected static $imageDir = __DIR__ . '/../images/';
    protected static $defaultHeaderImage = 'logo.png';
    protected static $views = [
        'header' => 'header.blade.php',
        'footer' => 'footer.blade.php',
        'body' => 'body.blade.php',
    ];

    public static function view(string $viewName, array $data = []): string
    {
        return View::file(self::$viewDir . $viewName, $data)->render();
    }

    /**
     * Configure mPDF settings with custom fonts and format options
     *
     * This method sets up the configuration for mPDF including:
     * - Font directories and custom font definitions for Khmer and Latin fonts
     * - Page orientation and size settings
     * - Script and language handling
     * - Temporary directory location
     * - Default margins and padding
     *
     * @param array ...$arg Variable number of config arrays to merge with default settings
     * @return array The complete merged configuration array
     *
     * @example
     * // Basic usage
     * $config = Template::config();
     *
     * // With custom overrides
     * $config = Template::config(['margin_top' => 30, 'format' => 'A5']);
     */
    public static function config(...$arg): array
    {
        $request = new Request();
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirectory = array_merge($defaultConfig['fontDir'], [self::$fontDir]);
        $defaultFontConfig  = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $configs = [
            'mode' => '+aCJK',
            "orientation" => $request->orientation ?: "L",
            "page_size" => $request->page_size ?: "A4",
            'format' => $request->format ?: "A4",
            "autoScriptToLang" => true,
            "autoLangToFont" => false,
            "tempDir" => self::$termDir,
            "fontDir" => array_merge(
                $fontDirectory,
                [self::$fontDir]
            ),
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
            'default_font' => 'georgia',
            'padding_header' => 100,
            'margin_top' => 28.346456693,
            'margin_left' => 15,
            'margin_right' => 15
        ];
        return array_merge($configs, ...$arg);
    }

    /**
     * Sets the HTML header for the mPDF document.
     *
     * @param \Mpdf\Mpdf $mpdf The mPDF instance to set the header on
     * @param string|null $header Optional custom HTML header content. If null, uses default template
     * @param string|null $headerImage Path to the header image (relative to imageDir or absolute)
     * @return void
     */
    public static function setHeader($mpdf, $header = null, $headerImage = null)
    {
        if ($header) {
            $mpdf->SetHTMLHeader($header);
        } else {
            // Determine image path - use provided image, default image, or none
            $imagePath = null;
            if ($headerImage) {
                // Check if it's an absolute path or a relative path
                $imagePath = file_exists($headerImage) ? $headerImage : self::$imageDir . $headerImage;
            } elseif (file_exists(self::$imageDir . self::$defaultHeaderImage)) {
                $imagePath = self::$imageDir . self::$defaultHeaderImage;
            }
            $mpdf->SetHTMLHeader(self::view(self::$views['header'], [
                'header' => (object)[
                    'company' => 'TURBOTECH CO.,LTD',
                    'title' => 'PDF Title',
                    'period' => 'Period'
                ],
                'headerImage' => $imagePath
            ]));
        }
    }

    /**
     * Sets the footer for the PDF document
     *
     * @param \Mpdf\Mpdf $mpdf The mPDF instance
     * @param string|null $footer Optional HTML footer content. If null, uses default footer template
     * @return void
     */
    public static function setFooter($mpdf, $footer = null)
    {
        $request = new Request();
        $isLandscape = $request->orientation == "L" ? true : false;
        if ($footer) {
            $mpdf->SetHTMLFooter($footer);
        } else {
            $mpdf->SetHTMLFooter(self::view(self::$views['footer'], ['isLandscape' => $isLandscape]));
        }
    }

    /**
     * Set watermark text on PDF document
     *
     * @param \Mpdf\Mpdf $mpdf The mPDF object instance
     * @param string|null $draft Custom watermark text (optional)
     * @return void
     *
     * This method applies a watermark to the PDF document using mPDF.
     * If no custom text is provided, it defaults to 'Draft'.
     * The watermark uses 'khmerosmoullight' font with 0.1 alpha transparency.
     */
    public static function setDraft($mpdf, $draft = null)
    {
        if ($draft) {
            $mpdf->SetWatermarkText($draft);
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'khmerosmoullight';
            $mpdf->watermarkTextAlpha = 0.1;
        } else {
            $mpdf->SetWatermarkText('Draft');
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'khmerosmoullight';
            $mpdf->watermarkTextAlpha = 0.1;
        }
    }

    /**
     * Generates and outputs a PDF document using mPDF.
     *
     * This method creates a PDF document with configurable margins, title and author.
     * It can include header, body content from views, and footer.
     * The PDF is directly outputted to the browser with copy and print protection.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance containing:
     *        - rows: Number of rows (default: 14)
     *        - cols: Number of columns (default: 20)
     *        - title: PDF title (default: 'PDF')
     *        - orientation/o: Page orientation L/P (default: 'L')
     * @return \Illuminate\Http\Response Streams PDF to browser
     * @throws \Mpdf\MpdfException When PDF generation fails
     *
     * @example
     * // Basic usage with default title
     * $controller->index($request);
     *
     * // Usage with custom title and orientation
     * $request->merge(['title' => 'Custom Report', 'orientation' => 'P']);
     * $controller->index($request);
     *
     * @uses \Mpdf\Mpdf For PDF generation
     * @uses view() For rendering template views
     * @uses env() For getting application name
     */
    public static function example(Request $request)
    {
        $rows = $request->rows ?: 14;
        $cols = $request->cols ?: 20;
        $title = $request->input('header_title', 'PDF');
        $orentation = $request->orientation ?: "L";
        $isLandscape = $orentation == "L" ? true : false;
        $rowLimit = 16;

        if (!$isLandscape) {
            $rowLimit = 28;
        }

        $config = self::config([
            'orientation' => $orentation,
            'margin_bottom' => $rows < $rowLimit ? ($isLandscape ? 54 : 10) : 14,
        ]);

        $pdfTitle = $title . "_R{$rows}C{$cols}" . "_" . date('dmY_His');

        $mpdf = new Mpdf($config);
        $mpdf->SetTitle($pdfTitle);
        $mpdf->SetAuthor(env('APP_NAME'));
        $mpdf->SetCreator(env('APP_NAME'));
        $mpdf->SetDisplayMode('fullpage');        $mpdf->SetProtection(['copy', 'print'], '', 'pass');
        $mpdf->SetCompression(true);

        $header = self::view(self::$views['header'], [
            'header' => (object)[
                'company' => $request->input('header_company', 'TURBOTECH CO.,LTD'),
                'title' => $request->input('header_title'),
                'period' => $request->input('sub_header_title'),
                'orientation' => $orentation,
            ],
            'headerImage' => $request->input('header_image', self::$imageDir . self::$defaultHeaderImage)
        ]);

        $body = self::view(self::$views['body'], [
            'rows' => $rows,
            'cols' => $cols,
        ]);

        $footer = self::view(self::$views['footer'], ['isLandscape' => $isLandscape]);

        $mpdf->WriteHTML(
            $header .
                $body .
                ($rows >= $rowLimit ? $footer : '')
        );

        if ($rows < $rowLimit) {
            // float footer
            self::setFooter($mpdf);
        }

        $mpdf->Output($pdfTitle . '.pdf', 'I');
    }

    /**
     * Generate PDF document using ERP template
     *
     * @param array ...$args Additional parameters to merge with request
     * @return void
     */
    public static function useERP(...$args)
    {
        $request = new Request();

        // អនុញ្ញាតឲ្យកំណត់ arguments បន្ថែមតាមរយៈ $args
        $request->merge(
            array_merge($request->all(), ...$args)
        );
        $rows = $request->rows ?: 14;
        $title = $request->header_title ?: 'PDF';
        $headerImage = $request->input('header_image', self::$imageDir . self::$defaultHeaderImage);
        $orentation = $request->orientation ?: "L";
        $isLandscape = strtoupper($orentation) === "L" ? true : false;
        $rowLimit = $isLandscape
            ? ($request->landscapeRowsLimit ?: 16) // ប្រភេទ Landscape ជា Default ១៦ rows ត្រូវ Break
            : ($request->portraitRowsLimit ?: 28); // ប្រភេទ Portrait ជា Default ២៨ rows ត្រូវ Break

        $config = self::config([
            // Configure​ គម្លាតផ្នែកខាងក្រោមនៃ PDF សម្រាប់លក្ខខណ្ឌ Landscape និង Portrait
            'margin_bottom' => $rows < $rowLimit ? ($isLandscape ? 54 : 10) : 14,
        ]);

        // កំណត់ឈ្មោះ PDF ដែលមានកាលបរិច្ឆេទនិងម៉ោង
        $pdfTitle = $title . "_" . date('dmY_His');

        $mpdf = new Mpdf($config);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor(env('APP_NAME'));
        $mpdf->SetCreator(env('APP_NAME'));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetCompression(true);

        // Header
        $header = self::view(self::$views['header'], [
            'header' => (object)[
                'company' => $request->input('header_company', 'TURBOTECH CO.,LTD'),
                'title' => $request->input('header_title'),
                'period' => $request->input('sub_header_title'),
                'orientation' => $orentation,
            ],
            'headerImage' => $headerImage
        ]);

        $body = $request->body ?: "";
        $footer = $rows >= $rowLimit
            ? self::view(self::$views['footer'], ['isLandscape' => $isLandscape])
            : '';
        $mpdf->WriteHTML($header . $body . $footer);

        if ($rows < $rowLimit) {
            // float footer
            self::setFooter($mpdf);
        }

        $mpdf->Output($pdfTitle . '.pdf', 'I');
    }
}