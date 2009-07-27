<?php

class User_IndexController extends Ig_Controller_Action
{
	public function indexAction()
	{
		if($this->user->id < 1)
		{
			$this->_redirect('/user/login');
		}else{
			$this->_redirect('/user/profile');
		}
	}
}