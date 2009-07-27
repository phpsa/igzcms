<?php

class User_ProfileController extends Ig_Controller_Action
{
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/index');
	}
	
}