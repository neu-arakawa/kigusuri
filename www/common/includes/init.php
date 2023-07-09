<?php
// インクルードパス追加
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));

function e($value, $default = '', $add_flag = false) {
  if (!empty($value)) {
    if ($add_flag && !empty($default)) {
      echo $value.' | '.$default;
    } else {
      echo $value;
    }
  } else {
    echo $default;
  }
}

if (!function_exists('h')) {
    function h($str='') {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

function eh($value, $default = '', $add_flag = false) {
    return e(h($value), h($default), $add_flag);
}

if(empty($_SESSION)){
    // session_save_path(dirname(__FILE__).'/../../../src/tmp/session');
    session_save_path('2;'.dirname(__FILE__).'/../../../src/tmp/session');
    session_start();
}

//端末判定
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$device = '';
if (
    (strpos($ua, 'windows') !== false && strpos($ua, 'touch') !== false && strpos($ua, 'tablet pc') === false) ||
    (strpos($ua, 'ipad') !== false) ||
    (strpos($ua, 'android') !== false && strpos($ua, 'mobile') === false) ||
    (strpos($ua, 'firefox') !== false && strpos($ua, 'tablet') !== false) ||
    (strpos($ua, 'kindle') !== false) ||
    (strpos($ua, 'silk') !== false) ||
    (strpos($ua, 'playbook') !== false) 

) {
    $device = 'tablet';
} elseif (
    (strpos($ua, 'windows') !== false && strpos($ua, 'phone') !== false ) ||
    (strpos($ua, 'iphone') !== false) ||
    (strpos($ua, 'ipod') !== false) ||
    (strpos($ua, 'android') !== false && strpos($ua, 'mobile') !== false) ||
    (strpos($ua, 'firefox') !== false && strpos($ua, 'mobile') !== false) ||
    (strpos($ua, 'blackberry') !== false) 
) {
    $device = 'mobile';
}

