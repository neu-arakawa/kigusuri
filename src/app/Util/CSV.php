<?php

namespace Util;

// date_default_timezone_set('Asia/Tokyo');

// $list = array(
    // array(
        // 'a' => 'あ',
        // 'b' => 'い'
    // ),
    // array(
        // 'a' => 'う',
        // 'b' => 'え'
    // ),
    // array(
        // 'a' => array(
            // 'あいうえお', 
            // 'かかかかか', 
        // ),
        // 'b' => 'え'
    // ),
// );

// $obj =  new CSV;
// $obj->filename = sprintf("products_%s.csv", date("Ymd")); 
// $obj->header = array(
    // 'a' => 'あのヘッダー',
    // 'b' => 'いのヘッダー',
// );
// $obj->rows = $list;
// $obj->dl();

class CSV{
   
  var $delimiter = "\t";
  var $enclosure = '"';
  var $filename = 'export.csv';
  var $rows = array();
  var $header;
   
  public function dl(){
     
    $fp = fopen('php://temp', 'r+b');
    $header_keys   = array_keys($this->header);
    $header_values = array_values($this->header);

    fputcsv($fp, $header_values, $this->delimiter, $this->enclosure); //1行目を書き込む
    foreach ($this->rows as $row) {
      $setField = array();
      foreach( $header_keys AS $key ){
          if( !isset($row[$key]) ){
              $row[$key] = '';
          }
          $val = $row[$key];
          if(is_array($val)){
            $val =  implode("\n", $val);
          }
          $setField[] = $val;
      }
      fputcsv($fp, $setField, $this->delimiter, $this->enclosure);
    }
    rewind($fp);
    $tmp = preg_replace("/\r\n|\r|\n/", "\r\n", stream_get_contents($fp));
    
    // echo $tmp;

    header('Content-Encoding: UTF-16LE');
    header('Content-type: text/csv; charset=UTF-16LE');
    header("Content-Disposition: attachment; filename=".$this->filename);
    
    $bom = chr(255) . chr(254); //BOM
    echo $bom . iconv("UTF-8", "UTF-16LE", $tmp);
  }
 
}
