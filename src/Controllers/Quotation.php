<?php

namespace Turbotech\PDFTemplate\Controllers;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use Turbotech\PDFTemplate\Controllers\Template;

class Quotation extends Template
{
    protected $params;

    public function __construct(?object $params = null)
    {
        $this->params = $params;
        $this->render();
    }

    /**
     * Get custom parameters for the quotation.
     *
     * @return object
     */
    public function getCustom()
    {
        return (object)[
            "options" => array_merge(
                [
                    "orientation" => $this->params->options->orientation ?? "L",
                    "title" => $this->params->options->title ?? "Quotation",
                    "logo" => request()->get('header_image', self::$imageDir . self::$defaultHeaderImage),
                ],
                (array)($this->params->options ?? []),
            ),
            'header' => (object)[
                "use_template" => $this->params->header->use_template ?? true,
                "template" => [
                    "name" => $this->params->header->template->name ?? "quotation"
                ],
                "view" => $this->params->header->view ?? null,
            ],
            'body' => $this->params->body ?? self::view(self::$views['quotation-example']),
            'footer' => (object)[
                "options" => (object)[],
                "use_template" => $this->params->footer->use_template ?? true,
                "template" => [
                    "name" => $this->params->footer->template->name ?? "quotation"
                ],
                "view" => $this->params->footer->view ?? null,
            ],
        ];
    }

    /**
     * Get the template name.
     *
     * @return string
     */
    public function getTemplateName()
    {
        return [
            "header" => 'headers/quotation.blade.php',
            "footer" => 'footers/quotation.blade.php'
        ];
    }

    /**
     * Get the template view.
     *
     * @return string
     */
    public function getTemplateView($type = 'header')
    {
        $params = (object)$this->getCustom()->options ?? (object)[];
        return View::file("{$this->getTemplateDir()}{$this->getTemplateName()[$type]}", compact('params'))->render();
    }

    /**
     * Get the header view.
     *
     * @return string
     */
    public function getHeader()
    {
        $params = $this->getCustom();
        return $params->header->use_template
            ? $this->getTemplateView('header')
            : $params->header->view ?? "No header view provided";
    }


    /**
     * Get the footer view.
     *
     * @return string
     */
    public function getFooter()
    {
        $params = $this->getCustom();
        return $params->footer->use_template
            ? $this->getTemplateView('footer')
            : $params->footer->view ?? "No footer view provided";
    }

    /**
     * Get the body view.
     *
     * @return string
     */
    public function getBody()
    {
        $params = $this->getCustom();
        return $params->body ?? "No body view provided";
    }


    /**
     * Render the PDF.
     *
     * @return void
     */
    public function render()
    {
        $rows = request()->get('rows', 14);
        $cols = request()->get('cols', 20);
        $title = request()->input('header_title', 'PDF');
        $orentation = request()->get('orientation', request()->get('o', "L"));


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

        $header = $this->getHeader();
        $body = $this->getBody();
        $footer = $this->getFooter();

        $mpdf->WriteHTML("
            <!DOCTYPE html>
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
                {$header} {$body}
            </body>
            </html>"
        );

        $mpdf->SetHTMLFooter($footer);
        $mpdf->Output($pdfTitle . '.pdf', 'I');
    }
}
