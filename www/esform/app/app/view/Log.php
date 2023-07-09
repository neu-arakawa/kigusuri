<?php

class Esform_View_Log extends Esform_ViewClass
{
	function preforward()
	{
		$logs = array();
		$fl = &$this->backend->getManager('FileList');
		$file_list = $fl->getList();
		foreach ($file_list as $file) {
			list(, $time) = explode('_', $file);
			$logs[] = array(
				'file' => $file,
				'time' => $time,
			);
		}
		$compare = create_function('$a, $b', 'return $a["time"] < $b["time"];');
		usort($logs, $compare);

		$this->af->setApp('logs', $logs);
		$this->af->setApp('id', $this->af->get('id'));
	}
}

?>
