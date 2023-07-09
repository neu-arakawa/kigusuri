<?php

class Esform_View_Enquete extends Esform_ViewClass
{
	function preforward()
	{
		$this->af->setApp('id', $this->af->get('id'));
	}
}

?>
