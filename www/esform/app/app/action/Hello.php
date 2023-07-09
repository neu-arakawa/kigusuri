<?php

class Esform_Form_Hello extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
	);
}
class Esform_Action_Hello extends Esform_ActionClass
{
	function authenticate()
	{
		if (is_file(C_CONFIG)) {
			return 'login';
		}
	}
	function perform()
	{
		return 'hello';
	}
}

?>
