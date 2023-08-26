<?php

namespace App\Services;

use App\Contracts\PdfViewInterface;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class LetterGeneratorService
{
    private $name;
    protected $extension = ".pdf";
    private $pdfView;

    // :TODO: [CV5-124] create an interface for LetterGeneratorService
    public function __construct(PdfViewInterface $pdfView)
    {
        $this->pdfView = $pdfView;
        $this->name = "document.{$this->extension}";
    }

    public function generate()
    {
        return PDF::loadview($this->pdfView->getView(), $this->pdfView->getData())
            ->setPaper('a4')
            ->setOption('images', true)
            ->setOption('enable-local-file-access', config('app.debug'))
            ->output();
    }

    public function download()
    {
        return PDF::loadview($this->pdfView->getView(), $this->pdfView->getData())
            ->setPaper('a4')
            ->setOption('images', true)
            ->setOption('enable-local-file-access', config('app.debug'))
            ->download($this->name);
    }

    public function setName($name)
    {
        // if $name includes $this->extension, remove it
        $name = str_replace($this->extension, '', $name);
        $this->name = $name . $this->extension;
        return $this;
    }

}
