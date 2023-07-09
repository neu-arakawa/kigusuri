<?php

require_once 'lib/Constants.php';

class Esform_Form_ProjectPwdDo extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'oldpass' => array(
				'type'         => VAR_TYPE_STRING,
				'form_type'    => FORM_TYPE_PASSWORD,
				'name'         => '現在のパスワード',
				'required'     => true,
				'custom'       => 'isAdmin',
			),
			'newpass' => array(
				'type'         => VAR_TYPE_STRING,
				'form_type'    => FORM_TYPE_PASSWORD,
				'name'         => '新しいパスワード',
				'required'     => true,
				'min'          => 6,
				'max'          => 16,
				'regexp'       => '{^[a-z\d_\-+.*/]+$}i',
				'custom'       => 'isDuplicate',
			),
			'chkpass' => array(
				'type'         => VAR_TYPE_STRING,
				'form_type'    => FORM_TYPE_PASSWORD,
				'name'         => '確認用パスワード',
				'required'     => true,
				'custom'       => 'isSame',
			),
	);
	function isAdmin($name)
	{
		$string = $this->form_vars[$name];

		if (md5($string) !== C_ADMIN_PASS) {
			$this->ae->add($name, '{form}が正しくありません', E_FORM_CONFIRM);
		}
	}
	function isDuplicate($name)
	{
		$string = $this->form_vars[$name];

		if (md5($string) === C_ADMIN_PASS) {
			$this->ae->add($name, '{form}が現在のパスワードです', E_FORM_CONFIRM);
		}
	}
	function isSame($name)
	{
		$newpass = $this->form_vars['newpass'];
		$chkpass = $this->form_vars['chkpass'];

		if ($newpass !== $chkpass) {
			$this->ae->add($name, '{form}が正しくありません', E_FORM_CONFIRM);
		}
	}
}
class Esform_Action_ProjectPwdDo extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'pwd';
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
		$constants->set('C_ADMIN_PASS', md5($this->af->get('newpass')));
		$constants->set('C_ADMIN_MAIL', C_ADMIN_MAIL);
		$constants->set('C_AUTO_SELECT', C_AUTO_SELECT);

		$fp = fopen(C_CONFIG, 'w+b');
		if (!$fp || !fwrite($fp, $constants->toString())) {
			$this->ae->add('error', C_CONFIG . 'に書き込めませんでした');
			return 'error';
		}
		fclose($fp);

		$this->session->destroy();
		$this->ae->add('error', 'パスワードを変更しました');
		return 'login';
	}
}

?>
