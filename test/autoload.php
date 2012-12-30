<?php

$path  = (string)get_include_path();
$path .= (string)(PATH_SEPARATOR . '../lib');

set_include_path($path);

spl_autoload_register(function ($className) {
    include_once($className . '.php');
});
