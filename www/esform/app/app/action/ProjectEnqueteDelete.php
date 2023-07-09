<?php

class Esform_Form_ProjectEnqueteDelete extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'id' => array(
					'type'         => VAR_TYPE_STRING,
					'name'         => 'ファイル番号',
					'required'     => true,
					'regexp'       => '/^[1-9]\d*$/',
			),
	);
}
class Esform_Action_ProjectEnqueteDelete extends Esform_Action_Project
{
	function perform()
	{
		if (!is_writable(C_LOG_DIR)) {
			$this->ae->add('error', C_LOG_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$id = $this->af->get('id');
		$file = sprintf('%s%03d_enquete.cgi', C_LOG_DIR, $id);
		if (!is_file($file)) {
			$this->ae->add('error', $file . 'が見つかりませんでした');
			return 'error';
		}
		if (!is_writable($file)) {
			$this->ae->add('error', $file . 'に書き込み権限がありません');
			return 'error';
		}
		unlink($file);

		return 'enquete';
	}
}

?>
