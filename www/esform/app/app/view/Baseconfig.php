<?php

class Esform_View_Baseconfig extends Esform_ViewClass
{
	function preforward()
	{
		$this->af->setApp('options', array('1' => 'はい', '0' => 'いいえ'));
		$this->af->setApp('admin_mail', $this->af->get('admin_mail'));
		$this->af->setApp('auto_select', $this->af->get('auto_select'));
	}
}

?>
