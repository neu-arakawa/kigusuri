<?php

//if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
//    return false;
//}

define('HOME', dirname(__DIR__));
// define('ROOT', dirname(__FILE__).'/../');
define('WWW_ROOT', dirname(__FILE__) );

require '../src/app.php';
$config_file = 'example';
if( $_SERVER['HTTP_HOST'] === 'www.kigusuri.com'){
    $config_file = 'production';
}
$app = new app($config_file);
$app->run();
