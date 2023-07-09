<?php

class Esform_Form_ProjectLogout extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
	);
}
class Esform_Action_ProjectLogout extends Esform_Action_Project
{
	function perform()
	{
		$this->session->destroy();
		$this->ae->add('error', 'ログアウトしました');
		return 'login';
	}
}
?>
