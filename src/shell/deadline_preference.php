<?php

require_once __DIR__.'/shell.php';

$config_file = null;
if( !empty($argv[1]) ) $config_file = $argv[1];
$app = new AppByShell($config_file);

//回答締切設定
$app->model('Question')->dead_answer();




echo 'ok';

