<?php

class User_ProfileController extends Ig_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->_model = new User;
	}
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/index');
	}
	
	public function forgotAction()
	{
		if($this->user->id > 0) $this->_redirect('/index/');
		if ($this->getRequest()->isPost()) 
		{
			$user = $this->_model->getUserByUsername($_POST['username']);
			if($user['id'] > 0){
				$newpass = substr(md5(strtotime("NOW")), 0,8);
				$encpass = md5($newpass);
				$user['password'] = $encpass;
				$this->_model->update($user);
				$this->_redirect('/index/','Your Password has been reset and mailed to you');
				}else{
					$this->view->notificationMessages[] = 'Sorry that username/email was not found in our database';
				}
		}
	}
	
	public function registerAction()
	{
		$form = new Form_Register();
		$this->view->form = $form;
		$request = $this->getRequest();
		if ($request->isPost()) 
		{
			$data = $_POST;
			if($this->_model->testUniqueEmail($data['email']))
			{
			//	echo $this->_model->testUniqueEmail($data['email']);
				$this->view->errors .= '<li>Email Address Already Registered';
				$form->addErrorMessage('Email already registered');
				
			}elseif($this->_model->testUniqueUsername($data['username']))
			{
				//$form->markAsError();
				$form->addErrorMessage('Username already registered');
				$this->view->errors .= '<li>Username Already Registered';
			}
			elseif($form->isValid($data))
			{
				$activation = $this->_model->register($data);
				if($activation)
				{
					$activationUrl = $this->cfg->getConfig('SITE_URL') . '/user/profile/activate/code/' . $activation . '/';
					//die($activation);
					$subject = 'User Account Created';
					$body = 'Dear ' . $data['name'] . '<br><Br>Your account has been created, before you can login you need to activate your account by clicking on the following link <a href="' .$activationUrl . '">' . $activationUrl . '</a>';
				}
				$this->_redirect('/user/profile/registersuccess/');
			}
			$form->populate($data);
		}
		
	}
	
	public function activateAction()
	{
		if($this->_model->activate($this->_getParam['code']))
		{
			$this->_redirect('/index', 'Account Activated, you can now login');
		}else{
			$this->_redirect('/index', 'Code not valid or account already activated');
		}
		
	}
	
	public function registersuccessAction()
	{
	}
	
}