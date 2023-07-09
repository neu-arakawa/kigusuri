<?php

class Esform_Form_ProjectEnquete extends Esform_ActionForm
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
class Esform_Action_ProjectEnquete extends Esform_Action_Project
{
	function perform()
	{
		if (!is_writable(C_LOG_DIR)) {
			$this->ae->add('error', C_LOG_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$form_name = '';
		$count = 0;
		$list = array();
		$id = $this->af->get('id');
		$file = sprintf('%s%03d_enquete.cgi', C_LOG_DIR, $id);
		if (is_file($file)) {
			$buffer = file_get_contents($file);
			$array = unserialize($buffer);
			$form_name = $array['name'];
			$count = count($array['host_list']);

			$list = $array['item_list'];
			foreach ($list as $key => $array) {
				$type = &$list[$key]['type'];
				$type = mb_convert_kana($type, 'k');
				$vote = &$list[$key]['vote'];
				arsort($vote);

				$total = array_sum($vote);
				$list[$key]['total'] = $total;
				$total = max($total, 1);
				$max = max(current($vote), 1);
				foreach ($vote as $name => $n) {
					$width = round($n / $max * 100);
					$rate = round($n / $total * 100, 1);
					$vote[$name] = array(
						'vote'  => $n,
						'width' => $width,
						'rate'  => $rate
					);
				}
			}
		} else {
			$list = array();
		}
		$this->af->setApp('form_name', $form_name);
		$this->af->setApp('count', $count);
		$this->af->setApp('list', $list);

		return 'enquete';
	}
}

?>
