<?php


if (!is_file('./app/app/Esform_Controller.php')) {
	exit;
}
require_once './app/app/Esform_Controller.php';

if (!is_file(C_CONFIG)) {
	print '設定ファイルが見つかりません。';
	exit;
}

require_once C_CONFIG;
Esform_Controller::main('Esform_Controller', array('post'));

/**
 * テンプレート関数
 */
function mb_date($time = 0)
{
	$time += time();
	$offset = (int)date('w', $time) * 3;
	$format = sprintf('n月j日（%s曜日）', substr('日月火水木金土', $offset, 3));
	return date($format, $time);
}
function error($var)
{
	if (!empty($var)) {
		printf('<em class="error">%s</em>', $var);
	}
	print "\n";
}
function error_br($var, $prebr = false)
{
	if (!empty($var)) {
		$br = $prebr ? '<br />' : '';
		printf('%s<em class="error">%s</em><br />', $br, $var);
	}
	print "\n";
}
function checked(&$var)
{
	if (!isset($var)) {
		$var = ' checked="checked"';
	}
}
function selected(&$var)
{
	if (!isset($var)) {
		$var = ' selected="selected"';
	}
}
function typed(&$var, $string)
{
	if (!isset($var)) {
		$var = htmlSpecialChars($string, ENT_QUOTES);
	}
}

?>
