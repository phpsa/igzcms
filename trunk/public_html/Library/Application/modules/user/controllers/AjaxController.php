<?php

class User_AjaxController extends Ig_Controller_Ajax
{
	
	public function init()
	{
		//TO DO 
		parent::init();
	}
	
	public function checkuserAction()
	{
		$user = new User();
		if($user->testUniqueUsername($_GET['username']))
		{
			$this->view->ajaxOut = 'false';
		}else{
			$this->view->ajaxOut = 'true';
		}
		//$this->view->ajaxOut = 'false';
		$this->_setCustomView('ajax');
	}
}