<?php

class Esform_Plugin_Validator_Extension extends Ethna_Plugin_Validator
{
	var $accept_array = true;

	function &validate($name, $var, $params)
	{
		if (empty($var['size'])) {
			return true;
		}

		$safety_ext_list = array(
			'jpg', 'png', 'gif', 'bmp',
			'doc', 'xls', 'ppt', 'txt', 'csv', 'pdf',
			'zip', 'lzh', 'sit', 'rar', 'tar', 'tgz'
		);

		$ext = preg_replace('/^.+\./', '', $var['name']);
		$ext = strToLower($ext);

		if (in_array($ext, $safety_ext_list)) {
			return true;
		}
		return Ethna::raiseNotice('{form}のファイルタイプは対応していません', E_FORM_INVALIDVALUE);
	}
}

?>
