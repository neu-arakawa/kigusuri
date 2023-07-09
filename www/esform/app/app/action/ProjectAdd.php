<?php

class Esform_Form_ProjectAdd extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'name' => array(
					'type'         => VAR_TYPE_STRING,
					'name'         => 'フォーム名',
					'filter'       => 'ntrim,trim,gpc',
					'required'     => true,
					'max'          => 80,
			),
			'file' => array(
					'type'         => VAR_TYPE_STRING,
					'name'         => 'ファイル名',
					'required'     => false,
					'max'          => 20,
					'regexp'       => '/^[a-z\d_\-]+$/',
			),
	);
}
class Esform_Action_ProjectAdd extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'project';
		}
	}
	function perform()
	{
		if (!is_writable(C_DATA_DIR)) {
			$this->ae->add('error', C_DATA_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$fl = &$this->backend->getManager('FileList');
		$file_list = &$fl->scandir(C_DATA_DIR);
		$files = count($file_list);
		if ($files === 0) {
			$id = 1;
		} else {
			if ($files >= C_PROJECT_LIMIT) {
				$this->ae->add('error', 'これ以上フォームを追加できません');
				return 'project';
			}
			list($id) = sscanf($file_list[0], '%03d.cgi');
			++$id;
		}
		$data_file = sprintf('%03d.cgi', $id);
		$data_file_path = C_DATA_DIR . $data_file;

		$name = $this->af->get('name');
		$file = $this->af->get('file');
		if ($file === '') {
			$file = Ethna_Util::getRandom(6);
		}
		if ($file !== basename($file, C_MOBILE_SUFFIX)) {
			$this->ae->add('error', C_MOBILE_SUFFIX . 'は予約語につき変更して下さい');
			return 'project';
		}

		$file_p = C_PUBLISH_DIR . $file . '.php';
		$file_m = C_PUBLISH_DIR . $file . C_MOBILE_SUFFIX . '.php';
		if (is_file($file_p) || is_file($file_m)) {
			$this->ae->add('error', $file . 'は使用中につき変更して下さい');
			return 'project';
		}

		$data = &$this->backend->getManager('Data');
		$data->load($data_file_path);
		$data->set('id', $id);
		$data->set('file', $file);
		$data->set('name', $name);
		$data->set('body',    'テンプレート作成時に作成されます。');
		$data->set('receipt', 'テンプレート作成時に作成されます。');
		$data->set('attr', array());
		if (!$data->write()) {
			$this->ae->add('error', $data_file_path . 'に書き込めませんでした');
			return 'error';
		}

		array_unshift($file_list, $data_file);
		$this->af->clearFormVars();
		return 'project';
	}
}

?>
