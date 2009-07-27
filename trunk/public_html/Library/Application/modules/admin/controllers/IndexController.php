<?php

class Admin_IndexController extends Ig_Controller_Admin
{
	
	public function indexAction()
	{
		$this->_checkAuth();
	}
}