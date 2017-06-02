<?php


class View
{
    private $contents = '';

    public function htmlFile($fileName)
    {
        //$this->contents = file_get_contents(ROOT_DIR . '/templates/' . $fileName . '.html');

        ob_start();
        require_once ROOT_DIR . '/templates/' . $fileName . '.phtml';
        $this->contents = ob_get_clean();
    }

    public function getContent()
    {
        return $this->contents;
    }
}
