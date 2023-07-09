<?php

class Esform_DataManager extends Ethna_AppManager
{
	var $file_path;
	var $array;

	function load($file_path)
	{
		$this->file_path = $file_path;
		if (is_file($file_path)) {
			$this->array = unserialize(file_get_contents($file_path));
		} else {
			$this->array = array();
		}
	}
	function &getArray()
	{
		return $this->array;
	}
	function set($name, $value)
	{
		$this->array[$name] = $value;
	}
	function &get($name)
	{
		if (isset($this->array[$name])) {
			return $this->array[$name];
		}
		$value = null;
		return $value;
	}
	function write()
	{
		$fp = fopen($this->file_path, 'wb');
		return $fp && fwrite($fp, serialize($this->array)) && fclose($fp);
	}
}

?>
