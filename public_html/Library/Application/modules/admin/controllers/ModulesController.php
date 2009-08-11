<?php

class Admin_ModulesController extends Ig_Controller_Admin
{
	public function init()
	{
		parent::init();
		$this->_model = new Modules();
	}
	public function indexAction()
	{
		$data = $this->_model->listModules($this->_getParam('page'));
		$this->view->data = $data;
		$this->_addButton('/admin/', 'Back', 'back');
		$this->view->moduleHeading = "Installed Modules";
	}
	
	public function listAction()
	{
		die('Here we will list all uninstalled modules');
	}
}
