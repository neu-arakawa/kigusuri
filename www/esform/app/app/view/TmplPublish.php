<?php

class Esform_View_TmplPublish extends Esform_ViewClass
{
	function preforward()
	{
		$form_file = $this->af->get('form_file');
		$form_file_p = C_PUBLISH_DIR . $form_file . '.php';
		$form_file_m = C_PUBLISH_DIR . $form_file . C_MOBILE_SUFFIX . '.php';
		$published_p = is_file($form_file_p);
		$published_m = is_file($form_file_m);
		$types = array(
			'r' => '既存テンプレートを再構築',
			'p' => 'PC用テンプレートを作成',
			'm' => '携帯用テンプレートを作成'
		);
		if (!$published_p && !$published_m) {
			array_shift($types);
		}
		$type = $this->af->get('type');
		if ($type == null) {
			$type = key($types);
		}
		$this->af->setApp('id', $this->af->get('id'));
		$this->af->setApp('index', $this->af->get('index'));
		$this->af->setApp('form_file', $form_file);
		$this->af->setApp('form_name', $this->af->get('form_name'));
		$this->af->setApp('types', $types);
		$this->af->setApp('type', $type);

		$list = array();
		if ($published_p) {
			$list[] = array(
				'id'   => 1,
				'name' => 'PC用',
				'file' => $form_file_p,
				'time' => filemtime($form_file_p)
			);
		}
		if ($published_m) {
			$list[] = array(
				'id'   => 2,
				'name' => '携帯用',
				'file' => $form_file_m,
				'time' => filemtime($form_file_m)
			);
		}
		$this->af->setApp('list', $list);
	}
}

?>
