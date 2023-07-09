<?php

class Twig_Filters extends Twig_Extension
{
  public function getFilters()
  {
    return array(
      't2d'      => new Twig_Filter_Function('Twig_Filters::t2d'),
      'hour'     => new Twig_Filter_Function('Twig_Filters::s2h'),
      'number'   => new Twig_Filter_Function('Twig_Filters::number'),
      'week_jp'  => new Twig_Filter_Function('Twig_Filters::week_jp'),
      'basename' => new Twig_Filter_Function('Twig_Filters::_basename'),
      'distance' => new Twig_Filter_Function('Twig_Filters::_distance'),
      'era'      => new Twig_Filter_Function('Twig_Filters::era'),
      'md5'      => new Twig_Filter_Function('Twig_Filters::_md5'),
      'year2era' => new Twig_Filter_Function('Twig_Filters::year2era'),
      'ascii'    => new Twig_Filter_Function('Twig_Filters::ascii'),
    );
  }

  static public function t2d($string)
  {
    return round(($string/8),1);
  }
  static public function number($str) {
    return (is_int($str)) ? number_format($str) : $str;
  }
  public function getName()
  {
    return 'project';
  }
  static public function s2h($sec)
  {
    return s2h($sec);
  }
  static public function week_jp($int)
  {
    $week = array(
        '日',
        '月',
        '火',
        '水',
        '木',
        '金',
        '土'
    );
    return !empty($week[$int]) ? $week[$int] : '';
  }
  public function _basename($name='')
  {
     return basename($name);
  }

  static function _distance($distance='')
  {
    if($distance > 1000){
        return round(($distance/1000),2).' [km]';
    }
    else {
        return round($distance).' [m]';
    }
  }

  static public function era($int)
  {
     if(10 > $int){
        return '10代未満';
     }
    
     return $int .'代';
  }

  static public function _md5($str='')
  {
     return md5($str);
  }

  static public function year2era($int)
  {
    return Twig_Filters::era(year2era($int));
  }

  static public function ascii($str='')
  {
    $result = preg_split("//u" , $str, -1, PREG_SPLIT_NO_EMPTY);
    $ord = array();
    foreach($result as $val){
        $ord[] = ord($val);
    }
    return implode(',',$ord);
  }

}
