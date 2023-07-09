<?php

class Constants
{
	var $list = array();

	function set($name, $value)
	{
		$this->list[$name] = $value;
	}
	function toString()
	{
		$list = array('<?php');
		foreach ($this->list as $name => $value) {
			$value = preg_replace('/[\r\n]+/', ',', $value);
			$value = mb_ereg_replace('\\\\', '\\\\', $value);
			$list[] = "define('$name', '$value');";
		}
		$list[] = '?>';
		$list[] = '';
		return implode("\n", $list);
	}
}

?>
