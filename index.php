<?php

const ROOT_DIR = __DIR__;

require_once 'config.php';

function customAutoloader($className)
{
    if (file_exists(__DIR__ . '/' . $className . '.php')) {
        require_once __DIR__ . '/' . $className . '.php';
        return true;
    }

    if (strpos($className, 'Controller') !== false) {
        require_once __DIR__ . '/controllers/' . substr($className, 0, -10) . '.php';
        return true;
    }

    if (strpos($className, 'Model') !== false) {
        require_once __DIR__ . '/models/' . substr($className, 0, -5) . '.php';
        return true;
    }
}

spl_autoload_register('customAutoloader');

require_once 'FrontController.php';

$fc = new FrontController();
$fc->start();
