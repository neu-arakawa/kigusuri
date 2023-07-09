<?php

require_once 'lib/Constants.php';

class Esform_Form_ProjectBaseconfigDo extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'admin_mail' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'メールアドレス',
				'required'     => false,
				'regexp'       => '/^[a-z\d_\-+@.,]+$/',
			),
			'auto_select' => array(
				'form_type'    => FORM_TYPE_RADIO,
				'type'         => VAR_TYPE_STRING,
				'name'         => '自動選択機能',
				'required'     => true,
			),
	);
}
class Esform_Action_ProjectBaseconfigDo extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'baseconfig';
		}
	}
	function perform()
	{
		if (!is_writable(C_DATA_DIR)) {
			$this->ae->add('error', C_DATA_DIR . 'に書き込み権限がありません');
			return 'error';
		}
		if (!is_writable(C_CONFIG)) {
			$this->ae->add('error', C_CONFIG . 'に書き込み権限がありません');
			return 'error';
		}

		$constants = new Constants();
		$constants->set('C_ADMIN_PASS', C_ADMIN_PASS);
		$constants->set('C_ADMIN_MAIL', $this->af->get('admin_mail'));
		$constants->set('C_AUTO_SELECT', $this->af->get('auto_select'));

		$fp = fopen(C_CONFIG, 'w+b');
		if (!$fp || !fwrite($fp, $constants->toString())) {
			$this->ae->add('error', C_CONFIG . 'に書き込めませんでした');
			return 'error';
		}
		fclose($fp);

		$this->ae->add('error', '設定ファイルを更新しました');
		return 'baseconfig';
	}
}

?>
