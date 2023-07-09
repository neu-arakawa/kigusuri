<?php

class Esform_FileListManager extends Ethna_AppManager
{
	var $list = array();

	function &scandir($dir, $regexp = '/^\d/')
	{
		$list = array();
		$dp = opendir($dir);
		while (($file = readdir($dp)) !== false) {
			if (preg_match($regexp, $file)) {
				$list[] = $file;
			}
		}
		closedir($dp);

		rsort($list);
		$this->list = $list;
		return $this->list;
	}
	function &getList()
	{
		return $this->list;
	}
}

?>
