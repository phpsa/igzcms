<?php

class Admin_MenuController extends Ig_Controller_Admin
{
	public function init()
	{
		parent::init();
		$this->_model = new Menus();
	}
	public function indexAction()
	{
		//Display list of menu's
		$this->view->menus = $this->_model->getMenus();
		$this->view->moduleHeading = "Menu List";
		//print_r($menus);
	}
	
	public function itemsAction()
	{
		//display list of items based on menu selected!
		$this->view->moduleHeading = "Menu List";
		$menuid = $this->_getParam('menu');
		$this->view->data = $this->_model->getMenuItemList($menuid, $this->_getParam("page"));
		$this->view->menuid = $menuid;
		$this->_addButton('/admin/menu', 'Back', 'back');
		$this->_addButton('/admin/menu/additem', 'Add New', 'add');
	}
	
	public function setorderAction()
	{
		$menuid = $this->_getParam('menu');
		$this->_model->updateMenuOrder($_POST['order']);
		$this->_redirect('/admin/menu/items/menu/' . $menuid , 'Menu Ordering Updated');
	}
	
	
}
