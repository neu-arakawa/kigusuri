<?php

class Esform_Form_ProjectFormAdd extends Esform_ActionForm
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
			'type' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'フォームタイプ',
				'required'     => true,
				'regexp'       => '/^[A-Z]+$/',
			),
	);
}
class Esform_Action_ProjectFormAdd extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'form';
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

		$count = count($data->get('attr'));
		if ($count >= C_FORM_LIMIT) {
			$this->ae->add('error', 'これ以上フォーム要素を追加できません');
			return 'form';
		}

		// id属性で使える様に英字開始にする
		$tmp = range('a', 'z');
		$form_id = $tmp[mt_rand(0, 25)] . Ethna_Util::getRandom(3);

		$type_nums = array(
			'TEXT'     => 1,
			'TEXTAREA' => 3,
			'SELECT'   => 4,
			'RADIO'    => 5,
			'CHECKBOX' => 6,
			'FILE'     => 8
		);

		$type = $this->af->get('type');
		$name = '未定義';

		if (!isset($type_nums[$type])) {
			$this->ae->add('error', 'typeの値が正しくありません');
			return 'form';
		}
		$type_name = $type;
		$form_type = $type_nums[$type];

		$var_types = array(
			'',
			VAR_TYPE_STRING,
			'',
			VAR_TYPE_STRING,
			VAR_TYPE_STRING,
			VAR_TYPE_STRING,
			array(VAR_TYPE_STRING),
			'',
			VAR_TYPE_FILE
		);
		$type = $var_types[$form_type];

		$max = $type_name == 'TEXTAREA' ? '3000' : '200';

		$attr = array(
			'id'           => $form_id,
			'name'         => $name,
			'type'         => $type,
			'form_type'    => $form_type,
			'type_name'    => $type_name,
			'required'     => '1',
			'min'          => '0',
			'max'          => $max,
			'regexp'       => '',
			'regexp_error' => '{form}を正しく入力して下さい',
			'example'      => '',
			'custom'       => '',
			'style'        => 'ime-mode: auto;',
			'width'        => '50',
			'height'       => '5',
			'suffix'       => '',
			'group'        => '0',
			'values'       => '選択項目,',
		);

		$index = $this->af->get('index') - 1;
		// 最後に追加
		if ($index > $count - 1) {
			$this->af->set('index', $count + 2);
		}

		$array = &$data->getArray();
		$attr_list = &$array['attr'];
		array_splice($attr_list, $index, 0, array($attr));
		if (!$data->write()) {
			$this->ae->add('error', $data_file . 'に書き込めませんでした');
			return 'error';
		}

		return 'form';
	}
}

?>
