<?php

class Esform_Form_ProjectFormModifyDo extends Esform_ActionForm
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
			'name' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '名称',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => true,
			),
			'required' => array(
				'type'         => VAR_TYPE_BOOLEAN,
				'name'         => '必須フラグ',
				'required'     => false,
			),
			'regexp' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '正規表現',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
				'min'          => 4,
			),
			'regexp_error' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'エラー文',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
			),
			'custom' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'フォーマット',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
			),
			'example' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '記入例',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
			),
			'values' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '選択項目',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
			),
			'style' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'スタイル',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
			),
			'max' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '文字数制限',
				'required'     => false,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'width' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '横幅',
				'required'     => false,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'height' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '縦幅',
				'required'     => false,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'suffix' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => '単位',
				'filter'       => 'ntrim,trim,gpc',
				'required'     => false,
			),
			'group' => array(
				'type'         => VAR_TYPE_BOOLEAN,
				'name'         => '結合フラグ',
				'required'     => false,
			),
	);
}
class Esform_Action_ProjectFormModifyDo extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		$this->af->validate();
		if ($this->ae->isError('id') || $this->ae->isError('index')) {
			return 'error';
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

		$index = $this->af->get('index') - 1;
		$array = &$data->getArray();
		if (!isset($array['attr'][$index])) {
			$this->ae->add('error', 'フォーム要素が見つかりませんでした');
			return 'error';
		}
		$attr = &$array['attr'][$index];
		$this->af->set('type_name', $attr['type_name']);

		// タイプ名が必要なのでここでエラー
		if ($this->ae->length() > 0) {
			return 'formModify';
		}

		// 保存用に加工
		$params = $this->af->getArray(false);
		$values = $this->af->get('values');
		$values = str_replace(',', '，', $values);
		$this->af->set('values', $values);
		$values = preg_replace('/\r\n?|\n/', ',', $values);
		$params['values'] = $values;

		// 不必要な項目を破棄
		unset($params['id'], $params['index']);
		$type = $params['type_name'][0];
		if ($type == 'S' || $ype == 'R' || $type = 'C') {
			unset($params['max']);
		}
		$attr = $params + $attr;

		if (!$data->write()) {
			$this->ae->add('error', $data_file . 'に書き込めませんでした');
			return 'error';
		}

		$this->af->set('preview', true);
		$this->ae->add('error', '変更を保存しました');
		return 'formModify';
	}
}

?>
