<?php


if($_SERVER["REMOTE_ADDR"] !== '160.86.231.249'){ 
    header("HTTP/1.0 404 Not Found");
    exit;
}

date_default_timezone_set('Asia/Tokyo');

// $cmd = 'df -hi /home/1906354514/kigusuri-com/';
// exec($cmd, $opt);
// print_r($opt);exit;
// echo exec('df -hi');exit;

//phpinfo();exit;
 
//削除期限
$expire = strtotime("720 hours ago");
 
//ディレクトリ
$dir = '../src/tmp/session/';
 
$list = scandir($dir);

echo count($list);
echo "<br><br>";


$i = 1;
$data = [];
foreach($list as $value){
    if( $i > 100) break;
    if($value === '.') continue;
    if($value === '..') continue;
    $file = $dir . $value;
    if(!is_file($file)) continue;
    $mod = filemtime( $file );
    if(date('Y-m-d', $mod) =='2019-11-11'){
        unlink($file);
        continue;
    }

    if(empty($data[date('Y-m-d', $mod)])){
        $data[date('Y-m-d', $mod)] = 0;
    }
    $data[date('Y-m-d', $mod)]++;

    // if($mod < $expire){
    //     //chmod($file, 0666);
    //     unlink($file);
    //     echo "$i: ".$file."<br>";
    //     $i++;
    // }
}

echo "<pre>";
var_dump($data);
