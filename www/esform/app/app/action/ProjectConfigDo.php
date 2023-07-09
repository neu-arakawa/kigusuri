<?php

class Esform_Form_ProjectConfigDo extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'id' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'ファイル番号',
				'required'     => true,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'name' => array(
					'type'         => VAR_TYPE_STRING,
					'name'         => 'フォーム名',
					'filter'       => 'ntrim,trim,gpc',
					'required'     => true,
					'max'          => 80,
			),
	);
}
class Esform_Action_ProjectConfigDo extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'config';
		}
	}
	function perform()
	{
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

		$name = $this->af->get('name');
		$data->set('name', $name);
		if (!$data->write()) {
			$this->ae->add('error', $data_file . 'に書き込めませんでした');
			return 'error';
		}

		$this->ae->add('error', '変更を保存しました');
		return 'config';
	}
}

?>
