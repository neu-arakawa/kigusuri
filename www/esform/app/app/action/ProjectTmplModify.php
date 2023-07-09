<?php

class Esform_Form_ProjectTmplModify extends Esform_ActionForm
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
				'required'     => false,
				'regexp'       => '/^[1-9]\d*$/',
			),
	);
}
class Esform_Action_ProjectTmplModify extends Esform_Action_Project
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

		$form_file = $data->get('file');
		if ($this->af->get('index') == '2') {
			$form_file .= C_MOBILE_SUFFIX;
		}
		$form_file = C_PUBLISH_DIR . $form_file . '.php';
		if (!is_file($form_file)) {
			$this->ae->add('error', $form_file . 'が見つかりませんでした');
			return 'error';
		}

		$source = file_get_contents($form_file);
		$this->af->set('source', $source);
		$this->af->setApp('form_file', $form_file);
		return 'tmplModify';
	}
}

?>
