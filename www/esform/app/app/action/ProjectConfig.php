<?php

class Esform_Form_ProjectConfig extends Esform_ActionForm
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
class Esform_Action_ProjectConfig extends Esform_Action_Project
{
	function perform()
	{
		$id = $this->af->get('id');
		$data_file = sprintf('%s%03d.cgi', C_DATA_DIR, $id);
		if (!is_file($data_file)) {
			$this->ae->add('error', $data_file . 'が見つかりませんでした');
			return 'error';
		}

		$data = &$this->backend->getManager('Data');
		$data->load($data_file);

		$this->af->set('name', $data->get('name'));
		return 'config';
	}
}

?>
