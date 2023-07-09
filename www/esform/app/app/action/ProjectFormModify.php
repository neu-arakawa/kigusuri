<?php

class Esform_Form_ProjectFormModify extends Esform_ActionForm
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
class Esform_Action_ProjectFormModify extends Esform_Action_Project
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

		$data = &$this->backend->getManager('Data');
		$data->load($data_file);

		$index = $this->af->get('index') - 1;
		$attr_list = $data->get('attr');
		if (!isset($attr_list[$index])) {
			$this->ae->add('error', 'フォーム要素が見つかりませんでした');
			return 'error';
		}

		$attr = $attr_list[$index];
		foreach ($attr as $k => $v) {
			$this->af->set($k, $v);
		}
		$values = str_replace(',', "\n", $attr['values']);
		$this->af->set('values', $values);
		$this->af->set('id', $id);
		$this->af->set('preview', true);

		return 'formModify';
	}
}

?>
