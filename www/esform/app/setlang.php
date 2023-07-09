<?php
ini_set("output_buffering", "On");
ini_set("output_handler", "mb_output_handler");
ini_set("default_charset", "UTF-8");
mb_language("Japanese");
mb_internal_encoding ("UTF-8");
mb_regex_encoding("UTF-8");
mb_http_input("auto");
mb_http_output("UTF-8");
mb_detect_order("auto");
mb_substitute_character("none");

header("Content-type: text/html; charset=utf-8");

function convert_encoding_deep($value)
{
    $currentCharset = "UTF-8";
    //$currentCharset = "SJIS";
	$value = is_array($value) ? array_map('convert_encoding_deep', $value) : mb_convert_encoding($value, $currentCharset, 'auto');
	return $value;
}

function convert_request_encoding()
{
    $_GET = convert_encoding_deep($_GET);
    $_POST = convert_encoding_deep($_POST);
}

convert_request_encoding();
?>