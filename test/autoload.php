<?php
defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__)));

$path  = get_include_path() . PATH_SEPARATOR;
$path .= BASE_PATH . '/../lib' . PATH_SEPARATOR;
$path .= BASE_PATH . '/lib';

set_include_path($path);

spl_autoload_register(function ($className) {
    if(strpos(strtolower($className), "weedphp") !== false) {
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        include_once($className . '.php');
    }
});
