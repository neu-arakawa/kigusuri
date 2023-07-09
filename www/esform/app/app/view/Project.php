<?php

class Esform_View_Project extends Esform_ViewClass
{
	function preforward()
	{
		$projects = array();
		$fl = &$this->backend->getManager('FileList');
		$file_list = $fl->getList();
		foreach ($file_list as $file) {
			list($id) = sscanf($file, '%03d.cgi');
			$path = C_DATA_DIR . $file;
			$project = unserialize(file_get_contents($path));
			$form_file_p = C_PUBLISH_DIR . $project['file'] . '.php';
			$form_file_m = C_PUBLISH_DIR . $project['file'] . C_MOBILE_SUFFIX . '.php';
			$project['form_file_p'] = $form_file_p;
			$project['form_file_m'] = $form_file_m;
			$project['published_p'] = is_file($form_file_p);
			$project['published_m'] = is_file($form_file_m);
			$project['count'] = count($project['attr']);
			$project['time'] = filemtime($path);
			$projects[] = $project;
		}

		$this->af->setApp('projects', $projects);
		$this->af->setApp('file', $this->af->get('file'));
		$this->af->setApp('name', $this->af->get('name'));
		$this->af->setApp('index', $this->af->get('index'));
	}
}

?>
