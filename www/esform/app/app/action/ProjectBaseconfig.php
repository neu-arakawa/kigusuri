<?php

class Esform_Form_ProjectBaseconfig extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
	);
}
class Esform_Action_ProjectBaseconfig extends Esform_Action_Project
{
	function perform()
	{
		$this->af->set('admin_mail', C_ADMIN_MAIL);
		$this->af->set('auto_select', C_AUTO_SELECT);
		return 'baseconfig';
	}
}

?>
