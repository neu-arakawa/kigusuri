<?php

require_once __DIR__.'/shell.php';

$app = new AppByShell();

$pharmacies = $app->model('Pharmacy')->find('list',array(
    'conditions'   => array(
        'topics_flg'   => true
    )
));

foreach($pharmacies as $pharmacy){
    $topics = getRss("http://www.kigusuri.com/shop/{$pharmacy['code']}/topic/feeds/rss2.0.xml");
    if(empty($topics))continue;
    foreach($topics as $topic){
        $topic['pharmacy_id'] = $pharmacy['id'];
        $app->model('Pharmacy_Topic')->save($topic);
    }
    sleep(1);
}

echo 'ok';


function xmlLoad($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
    $fileContents = curl_exec($ch);
    curl_close($ch);

    $fileContents = str_replace( array(
      "\n",
      "\r",
      "\t"
    ), '', $fileContents );
    $fileContents = trim( str_replace( '"', "'", $fileContents ) );
    return @simplexml_load_string( $fileContents );
}

function getRss($url){
    //$rss = @simplexml_load_file($url); //wadaxでは使えない
    $rss = xmlLoad($url);
    if(!$rss){
        return array();
    }
    $list = array();
    foreach ( $rss->channel->item as $item ) {
        $data = array();
        $data['ident']     = md5($item->guid);
        $data['category']  = $item->category;
        $data['title']  = $item->title;
        $data['link']   = $item->link;
        $data['date']   = date("Y-m-d H:i:s", strtotime($item->pubDate));
        $data['description'] = str_replace(array("　","\r\n","\r","\n"), '',mb_substr($item->description, 0 , -5));
        $list[] = $data;
    }

    return $list;
}
