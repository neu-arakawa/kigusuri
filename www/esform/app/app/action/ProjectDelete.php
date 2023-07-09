<?php

class Esform_Form_ProjectDelete extends Esform_ActionForm
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
class Esform_Action_ProjectDelete extends Esform_Action_Project
{
	function perform()
	{
		if (!is_writable(C_PUBLISH_DIR)) {
			$this->ae->add('error', C_PUBLISH_DIR . 'に書き込み権限がありません');
			return 'error';
		}
		if (!is_writable(C_DATA_DIR)) {
			$this->ae->add('error', C_DATA_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$id = $this->af->get('id');
		$data_file = sprintf('%s%03d.cgi', C_DATA_DIR, $id);
		if (!is_file($data_file)) {
			$this->ae->add('error', $data_file . 'が見つかりませんでした');
			return 'error';
		}
		if (!is_writable($data_file)) {
			$this->ae->add('error', $data_file . 'に書き込み権限がありません');
			return 'error';
		}

		$data = &$this->backend->getManager('Data');
		$data->load($data_file);

		// データファイル
		unlink($data_file);

		// テンプレート
		$file = $data->get('file');
		$form_file = C_PUBLISH_DIR . $file . '.php';
		if (is_file($form_file)) {
			if (!is_writable($form_file)) {
				$this->ae->add('error', $form_file . 'に書き込み権限がありません');
				return 'error';
			}
			unlink($form_file);
		}
		$form_file = C_PUBLISH_DIR . $file . C_MOBILE_SUFFIX . '.php';
		if (is_file($form_file)) {
			if (!is_writable($form_file)) {
				$this->ae->add('error', $form_file . 'に書き込み権限がありません');
				return 'error';
			}
			unlink($form_file);
		}

		// 送信ログと集計結果
		$fl = &$this->backend->getManager('FileList');
		if (is_writable(C_LOG_DIR)) {
			$file_list = &$fl->scandir(C_LOG_DIR, sprintf('/^%03d_/', $id));
			foreach ($file_list as $file) {
				unlink(C_LOG_DIR . $file);
			}
			$file_list = array();
		}
		$fl->scandir(C_DATA_DIR);

		return 'project';
	}
}

?>
