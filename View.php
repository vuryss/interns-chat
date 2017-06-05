<?php


class View
{
    private $contents = '';

    public function htmlFile($fileName, $data = [])
    {
        extract($data);

        ob_start();
        require_once ROOT_DIR . '/templates/' . $fileName . '.phtml';
        $this->contents = ob_get_clean();
    }

    public function json($data)
    {
        header('Content-Type: application/json');
        $this->contents = json_encode($data);
    }

    public function getContent()
    {
        return $this->contents;
    }
}
