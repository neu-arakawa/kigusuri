<?php

class Esform_Form_ProjectLogDelete extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'file' => array(
					'type'         => VAR_TYPE_STRING,
					'name'         => 'ファイル名',
					'required'     => true,
					'regexp'       => '/^\d{3}_\d+_[a-f\d]+\.cgi$/',
			),
	);
}
class Esform_Action_ProjectLogDelete extends Esform_Action_Project
{
	function perform()
	{
		if (!is_writable(C_LOG_DIR)) {
			$this->ae->add('error', C_LOG_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$file = $this->af->get('file');
		$log_file = C_LOG_DIR . $file;
		if (!is_file($log_file)) {
			$this->ae->add('error', $log_file . 'が見つかりませんでした');
			return 'error';
		}
		if (!is_writable($log_file)) {
			$this->ae->add('error', $log_file . 'に書き込み権限がありません');
			return 'error';
		}
		unlink($log_file);

		list($id) = sscanf($file, '%03d');
		$this->af->set('id', $id);

		$fl = &$this->backend->getManager('FileList');
		$file_list = $fl->scandir(C_LOG_DIR, sprintf('/^%03d_\d/', $id));

		return 'log';
	}
}

?>
