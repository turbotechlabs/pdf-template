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
        'quotation-example' => 'quotation-example.blade.php',
    ];

    public static function getViewDir(): string
    {
        return __DIR__ . '/../views/';
    }

    public static function getTemplateDir(): string
    {
        return self::getViewDir() . 'templates/';
    }

    public static function view(string $viewName, array $data = []): string
    {
        return View::file(self::$viewDir . $viewName, $data)->render();
    }

    /**
     * Configures the PDF settings for mPDF.
     *
     * This static method sets up the configuration for mPDF, including font directories,
     * default fonts, page orientation, and other parameters. It merges any additional
     * arguments provided into the configuration array.
     *
     * @param mixed ...$arg Additional configuration parameters to merge
     * @return array The complete configuration array for mPDF
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
     * Sets the header for the PDF document
     *
     * @param \Mpdf\Mpdf $mpdf The mPDF instance
     * @param string|null $header Optional HTML header content. If null, uses default header template
     * @param string|null $headerImage Optional path to a header image. If null, uses default image
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
     * Generate a PDF document with example content.
     *
     * This static method creates a PDF based on the provided request parameters,
     * allowing customization of rows, columns, header title, and orientation.
     * It supports both landscape and portrait orientations with dynamic row limits.
     *
     * @param Request $request The HTTP request containing parameters for PDF generation
     * @return void The method outputs the PDF directly to the browser with 'inline' disposition
     *
     * @uses Mpdf For PDF generation
     * @uses self::config() To set up PDF configuration
     * @uses self::view() To render template views
     * @uses self::setFooter() To apply footer when content is below row limit
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
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetProtection(['copy', 'print'], '', 'pass');
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
     * Generate a PDF document using ERP template settings.
     *
     * This static method creates a PDF based on configuration settings and content provided
     * via the request or additional arguments. It supports both landscape and portrait orientations
     * with dynamic row limits.
     *
     * Features:
     * - Allows additional parameter merging through variadic arguments
     * - Customizable header with company name, title, and image
     * - Automatic page orientation handling (landscape/portrait)
     * - Dynamic margin adjustment based on content length
     * - Automatic footer placement logic
     * - PDF metadata (title, author, creator)
     *
     * @param array ...$args Additional parameters to merge with the current request
     * @return void The method outputs the PDF directly to the browser with 'inline' disposition
     *
     * @uses Mpdf For PDF generation
     * @uses self::config() To set up PDF configuration
     * @uses self::view() To render template views
     * @uses self::setFooter() To apply footer when content is below row limit
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
            'orientation' => $orentation,
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
