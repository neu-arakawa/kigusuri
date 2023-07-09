<?php

class Esform_View_TmplModify extends Esform_ViewClass
{
	function preforward()
	{
		$this->af->setApp('type', $this->af->get('type'));
		$this->af->setApp('id', $this->af->get('id'));
		$this->af->setApp('index', $this->af->get('index'));
		$this->af->setApp('source', $this->af->get('source'));
	}
}

?>
