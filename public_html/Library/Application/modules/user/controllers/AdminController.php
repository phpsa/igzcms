<?php

class User_AdminController extends Ig_Controller_Admin
{
	public function init()
	{
		parent::init();
		$this->_model = new User();
	}
	
	public function indexAction()
	{
		$data = $this->_model->listUsers($this->_getParam('page'));
		$this->view->data = $data;
		$this->_addButton('/admin/', 'Back', 'back');
		$this->_addButton('/admin/acl','Manage ACL');
	}
}
