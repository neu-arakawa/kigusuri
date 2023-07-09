<?php

class Esform_View_Hello extends Esform_ViewClass
{
	function preforward()
	{
		$this->af->setApp('admin_pass', $this->af->get('admin_pass'));
		$this->af->setApp('admin_mail', $this->af->get('admin_mail'));
	}
}

?>
