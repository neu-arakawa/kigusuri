<?php

class Esform_Form_ProjectPwd extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
	);
}
class Esform_Action_ProjectPwd extends Esform_Action_Project
{
	function perform()
	{
		return 'pwd';
	}
}

?>
