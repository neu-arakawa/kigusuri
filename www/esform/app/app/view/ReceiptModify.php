<?php

class Esform_View_ReceiptModify extends Esform_ViewClass
{
	function preforward()
	{
		$this->af->setApp('id', $this->af->get('id'));
		$this->af->setApp('body', $this->af->get('body'));
	}
}

?>
