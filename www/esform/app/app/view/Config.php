<?php

class Esform_View_Config extends Esform_ViewClass
{
	function preforward()
	{
		$this->af->setApp('id', $this->af->get('id'));
		$this->af->setApp('name', $this->af->get('name'));
	}
}

?>
