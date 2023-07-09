<?php

class Esform_Form_ProjectFormDown extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'id' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'ファイル番号',
				'required'     => true,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'index' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'フォーム番号',
				'required'     => true,
				'regexp'       => '/^[1-9]\d*$/',
			),
	);
}
class Esform_Action_ProjectFormDown extends Esform_Action_Project
{
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

		$attr_list = &$data->get('attr');
		$index = $this->af->get('index') - 1;
		if ($index < count($attr_list) - 1) {
			$array = array_splice($attr_list, $index, 1);
			array_splice($attr_list, $index + 1, 0, $array);
			if (!$data->write()) {
				$this->ae->add('error', $data_file . 'に書き込めませんでした');
				return 'error';
			}

			$this->af->set('index', $index + 2);
		}
		return 'form';
	}
}

?>
