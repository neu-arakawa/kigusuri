<?php

class Esform_Form_Login extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'pass' => array(
				'type'         => VAR_TYPE_STRING,
				'form_type'    => FORM_TYPE_PASSWORD,
				'name'         => 'パスワード',
				'required'     => true,
				'custom'       => 'isAdmin',
			),
	);
	function isAdmin($name)
	{
		$value = $this->form_vars[$name];

		if (md5($value) !== C_ADMIN_PASS) {
			$this->ae->add($name, '{form}が正しくありません', E_FORM_CONFIRM);
		}
	}
}
class Esform_Action_Login extends Esform_ActionClass
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'login';
		}
	}
	function perform()
	{
		$this->session->start();

		$host = getEnv('HTTP_HOST');
		$port = getEnv('SERVER_PORT');
		$ssl = getEnv('HTTPS');
		$ssl = strToLower($ssl);
		$is_ssl = $ssl == 'on';
		$scheme = $is_ssl ? 'https://' : 'http://';
		if ($port != null) {
			if ((!$is_ssl && $port !== '80') || ($is_ssl && $port !== '443')) {
				$port = ':' . $port;
			} else {
				$port = '';
			}
		}

		$path = $_SERVER['PHP_SELF'];
		$path = htmlSpecialChars($path, ENT_QUOTES);
		$dir = rtrim(dirname($path), '/\\');
		$dir .= '/';
		$file = getEnv('SCRIPT_FILENAME');
		$file = basename($file);
		$url = $scheme . $host . $port . $dir . $file;
		header('Location: ' . $url . '?mode=project');
		return null;
	}
}

?>
