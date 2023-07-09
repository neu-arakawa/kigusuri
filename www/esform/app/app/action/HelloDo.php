<?php

require_once 'lib/Constants.php';

class Esform_Form_HelloDo extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'admin_pass' => array(
				'type'         => VAR_TYPE_STRING,
				'form_type'    => FORM_TYPE_PASSWORD,
				'name'         => 'パスワード',
				'required'     => true,
				'min'          => 6,
				'max'          => 16,
				'regexp'       => '/^[a-z\d_\-+@.,]+$/',
			),
			'admin_mail' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'メールアドレス',
				'required'     => false,
				'regexp'       => '/^[a-z\d_\-+@.,]+$/',
			),
	);
}
class Esform_Action_HelloDo extends Esform_ActionClass
{
	function authenticate()
	{
		if (is_file(C_CONFIG)) {
			return 'login';
		}
	}
	function prepare()
	{
		if (empty($_POST)) {
			return 'hello';
		}
		if ($this->af->validate() > 0) {
			return 'hello';
		}
	}
	function perform()
	{
		if (!is_writable(C_DATA_DIR)) {
			$this->ae->add('error', C_DATA_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$constants = new Constants();
		$constants->set('C_ADMIN_PASS', md5($this->af->get('admin_pass')));
		$constants->set('C_ADMIN_MAIL', $this->af->get('admin_mail'));
		$constants->set('C_AUTO_SELECT', '0');

		$fp = fopen(C_CONFIG, 'w+b');
		if (!$fp || !fwrite($fp, $constants->toString())) {
			$this->ae->add('error', C_CONFIG . 'に書き込めませんでした');
			return 'error';
		}
		fclose($fp);

		return 'login';
	}
}

?>
