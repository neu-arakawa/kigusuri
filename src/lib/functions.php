<?php

function pr($data=array()){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
function is($data=false){
    if(!empty($data)){
        return true;
    }
    return false;
}
function not($data=false){
    return !is($data);
}
function time2day($time){
    return round(($time/8),1);
}
function s2h($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;
    $hms = sprintf("%02d:%02d", $hours, $minutes);
    return $hms;
}
function h2s($hours) {
    $t = explode(":", $hours);
    $h = $t[0];
    if (isset($t[1])) {
    $m = $t[1];
    } else {
    $m = "0";
    }
    if (isset($t[2])) {
    $s = $t[2];
    } else {
    $s = "0";
    }
    return ($h*60*60) + ($m*60) + $s;
}
function mkdirr($dirName, $rights=0777){
    $dirs = explode('/', $dirName);
    $dir='';
    foreach ($dirs as $part) {
        $dir.=$part.'/';
        if (!is_dir($dir) && strlen($dir)>0)
            mkdir($dir, $rights);
    }
}

function month_last_days($year, $month){

    $month = $year. '-' .$month;
    return date('d', strtotime('last day of ' . $month));
}
function month_first_last_days($year, $month){

    $month = $year. '-' .$month;
    return  array(
        date('d', strtotime('first day of ' . $month)),
        date('d', strtotime('last day of ' . $month))
    );
}

//csv
setlocale(LC_ALL, 'ja_JP.UTF-8');
function csvtxt2array($str=null, $encode='sjis'){
    $data = rtrim($str);
    $data = preg_replace("/\t/",",",$data); #tsv → csv
    $data = preg_replace("/\r\n|\r|\n/","\n",$data);
    if($encode === 'sjis' ){
        $data = mb_convert_encoding($data, 'utf-8', 'sjis-win');
    }
    $temp = tmpfile();
    $csv  = array();
     
    fwrite($temp, $data);
    rewind($temp);
      
    while (($data = fgetcsv($temp, 0, ",")) !== FALSE) {
        $csv[] = $data;
    }
    fclose($temp);
    return $csv;
}

function t2i($str){
    $str = str_replace(',','',$str);
    if(is_numeric($str)){
        return (double)($str);
    }

    return null;
}
function is_email($addr) {
    $atom       = "[a-zA-Z0-9_!#\$\%&'*+\\/=?\^`{}~|\-]+";
    $dot_atom   = "$atom(?:\.$atom)*";
    $quoted     = '"(?:\\[^\r\n]|[^\\"])*"';
    $local      = "(?:$dot_atom|$quoted)";
    $domain_lit = "\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\]";
    $domain     = "(?:$dot_atom|$domain_lit)";
    $addr_spec  = $local.'@'.$domain;
    return preg_match('/^'.$addr_spec.'$/', $addr);
}

function merging_array($ar1, $ar2){
    foreach($ar2 as $key => $val){
        $ar1[$key] = $val;
    }
    return $ar1;
}


function delete4byte($str){
    return preg_replace('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', '', $str);
}

function delete4byte_array($array){
    foreach($array as $key => $value){
        if(is_array($data)){
            continue;
        }
        $array[$key] = delete4byte($value);
    }
    return $array;
}
if (!function_exists('password_verify')){
    function password_verify($password, $hash){
        return (crypt($password, $hash) === $hash);
    }
}
function is_openhours($text) {
    #空文字チェック
    if($text == ''){
        return false;
    }

    $pattern  = '/^([0-9]{1,2}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})';
    $pattern .= '-([0-9]{1,2}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})';
    $pattern .= '$/';
    
    if(!preg_match($pattern, $text, $matches)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $url);
}
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/@\[[^\]]++\]\z/', $email);
}
function is_zip($zip) { 
    return preg_match("/^\d{3}\-?\d{4}$/",$zip);
}

function trim_emspace ($str) {
    $str = preg_replace('/^[ 　]+/u', '', $str);
    $str = preg_replace('/[ 　]+$/u', '', $str);
    return $str;
}

function text2tag_array($target){
    $flg = 0;
    $text = '';
    foreach(preg_split('//u', $target, -1, PREG_SPLIT_NO_EMPTY) as $c){
    if (preg_match("/(（|\()/", $c)) $flg++;
    if (preg_match("/(）|\))/", $c) && $flg > 0) $flg--;
        if (preg_match("/(、|・)/", $c) && $flg == 0) {
            $text .= ',';
        } else {
         $text .= $c;
        }
    }
    
    $array = explode(",",$text);
    foreach($array as $key => $val){
       $array[$key] = trim_emspace($val);
    }

    return $array;
}

function is_valid_phone_number($number) {
    return is_string($number) && preg_match('/\A\d{2,4}+-\d{2,4}+-\d{2,4}\z/', $number);
}

function year2era($year){
    if(!is_numeric($year))return false;
    $year = $year.'0101';
    $age  = floor ((date('Ymd') - $year)/10000);
    if($age>10){
        return mb_substr( $age , 0 , mb_strlen($age)-1 ).'0';
    }
    return 0;
}

function safe_nickname($str){
    return !preg_match("/[^ぁ-んァ-ンーa-zA-Z0-9一-龠０-９\-\r]+/u", $str);
}

function password_encryption($password,$salt){
    return sha1(crypt($password, $salt).$password);
}
