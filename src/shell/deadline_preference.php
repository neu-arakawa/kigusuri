<?php

require_once __DIR__.'/shell.php';

$app = new AppByShell();

//回答締切設定
$app->model('Question')->dead_answer();




echo 'ok';

