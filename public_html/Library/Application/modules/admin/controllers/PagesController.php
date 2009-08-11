<?php

class Admin_PagesController extends Ig_Controller_Admin
{
	public function init()
	{
		parent::init();
		$this->_model = new Page();
		$this->_checkAuth();
	}
	
	public function indexAction()
	{
		$this->view->moduleHeading = "Page Manager";
		$this->_addButton('/admin/pages/add/', 'Add', 'add');
		//$this->_addButton('/admin/pages/add/', 'Add2');
		$this->view->pages = $this->_model->listPages($this->_getParam('page'));
	}
	
	public function editAction()
	{
		$this->view->moduleHeading = "Edit Page";
		$this->_addSubmit();
		$this->_addButton('/admin/pages', 'Cancel', 'cancel');
		
		$myrole = isset($this->user->role)?$this->user->role:'Guests';
		$page = $this->_model->getPage($this->_getParam('id'));
		if(!$page['id'])
		{
			$this->_redirect('/admin/pages', 'Page not Found');
		}
		
		$role = $page['role'];
		$this->_setAccess($role,"page:{$page['id']}");
		if( $this->_isAllowed($myrole,"page:{$page['id']}") < 1)
		{
			$this->_redirect('/admin/pages', 'Not Authorised to edit this content');
		}
		$this->view->page = $page;
		
	}
	
	public function saveAction()
	{
		$page = $_POST;
		//print_r($page);
	//	die();
		$this->_model->save($page);
		
		$this->_redirect('/admin/pages', 'Page Content Updated');
	}
	
	public function addAction()
	{
		$page = array();
		$this->_setCustomView('edit');
		$this->view->moduleHeading = "Add Page";
		$this->_addSubmit();
		$this->_addButton('/admin/pages', 'Cancel', 'cancel');
		//show form
	}
	
	public function deleteAction()
	{
		$page = array();
		$page['id'] = $this->_getParam("id");
		$page['deleted'] = '1';
		$this->_model->save($page);
		$this->_redirect("/admin/pages", "Page Deleted");
	}
	
	public function menuAction()
	{
		
	}
}