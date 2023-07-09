<?php
require_once './app/setlang.php';
require_once './app/app/Esform_Controller.php';

if (is_file(C_CONFIG)) {
	include_once C_CONFIG;
	Esform_Controller::main('Esform_Controller', 'project', 'project');
} else {
	Esform_Controller::main('Esform_Controller', array('hello', 'hello_*'));
}

?>
