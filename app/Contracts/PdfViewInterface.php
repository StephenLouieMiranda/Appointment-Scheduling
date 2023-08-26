<?php

namespace App\Contracts;

interface PdfViewInterface
{
    /**
     * Sets the blade file string of the view
     * @param string $view
     */
    public function setView(string $view);

    /**
     * returns the view string
     * @return string
     */
    public function getView(): string;

    /**
     * retrieves the data for the view
     * @return array
     */
    public function getData(): array;
}