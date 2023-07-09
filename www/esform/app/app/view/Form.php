<?php

require_once 'lib/FormBuilder.php';

class Esform_View_Form extends Esform_ViewClass
{
	function preforward()
	{
		$type = $this->af->get('type');
		if ($type === null) {
			$type = 'TEXT';
		}
		$type_list = array(
			'TEXT'     => '一行入力',
			'TEXTAREA' => '複数行入力',
			'SELECT'   => 'セレクトボックス',
			'RADIO'    => 'ラジオボタン',
			'CHECKBOX' => 'チェックボックス',
			'FILE'     => 'ファイル選択'
		);

		$data = &$this->backend->getManager('Data');

		$index_list = range(0, count($data->get('attr')) + 1);
		unset($index_list[0]);

		$this->af->setApp('id', $this->af->get('id'));
		$this->af->setApp('index', $this->af->get('index'));
		$this->af->setApp('index_list', $index_list);
		$this->af->setApp('type', $type);
		$this->af->setApp('type_list', $type_list);
		$this->af->setApp('form_name', $data->get('name'));

		$builder = new FormBuilder();
		$forms = array();
		$attr_list = $data->get('attr');
		foreach ($attr_list as $attr) {
			extract($attr);

			$attr['width'] = 1;
			$attr['height'] = 1;
			$attr['values'] = ' ';
			$form = $builder->$type_name($attr);
			$form = preg_replace('/\<\?.+?\?\>/', '', $form);
			$attr['form'] = $form;

			$forms[] = $attr;
		}
		$this->af->setAppNE('forms', $forms);
	}
}

?>
