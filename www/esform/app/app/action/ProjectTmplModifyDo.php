<?php

class Esform_Form_ProjectTmplModifyDo extends Esform_ActionForm
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
			'source' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'テンプレートソース',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => true,
			),
	);
}
class Esform_Action_ProjectTmplModifyDo extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'tmplModify';
		}
	}
	function perform()
	{
		if (!is_writable(C_PUBLISH_DIR)) {
			$this->ae->add('error', C_PUBLISH_DIR . 'に書き込み権限がありません');
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

		$form_file = $data->get('file');
		if ($this->af->get('index') == '2') {
			$form_file .= C_MOBILE_SUFFIX;
		}
		$form_file = C_PUBLISH_DIR . $form_file . '.php';
		if (!is_writable($form_file) && is_file($form_file)) {
			$this->ae->add('error', $form_file . 'に書き込み権限がありません');
			return 'error';
		}

		$source = $this->af->get('source');
		if (strpos($source, C_FORM_DIR) === false) {
			unlink($data_file);
		}

		$fp = fopen($form_file, 'w+b');
		if (!$fp || !fwrite($fp, $source)) {
			$this->ae->add('error', $form_file . 'に書き込めませんでした');
			return 'error';
		}
		fclose($fp);

		$this->ae->add('error', '変更を保存しました');
		$this->af->setApp('form_file', $form_file);
		return 'tmplModify';
	}
}

?>
