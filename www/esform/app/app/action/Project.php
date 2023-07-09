<?php

class Esform_Form_Project extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'index' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'フォーム番号',
				'required'     => false,
				'regexp'       => '/^[1-9]\d*$/',
			),
	);
}
class Esform_Action_Project extends Esform_ActionClass
{
	function authenticate()
	{
		if (!$this->session->isStart()) {
			return 'login';
		}
	}
	function prepare()
	{
		if ($this->af->validate() > 0) {
			return 'error';
		}
	}
	function perform()
	{
		if (!is_writable(C_DATA_DIR)) {
			$this->ae->add('error', C_DATA_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$fl = &$this->backend->getManager('FileList');
		$fl->scandir(C_DATA_DIR);
		return 'project';
	}
}

?>
