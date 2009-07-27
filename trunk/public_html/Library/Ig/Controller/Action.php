<?php

Class Ig_Controller_Action extends Zend_Controller_Action
{
	public $view;
	public $user;
	
	public function init()
	{
		$acl = Zend_Registry::get("ACL");
		$this->_loadUser();
		
		$role = isset($this->user->role)?$this->user->role:'Guests';
		$view = Zend_Layout::getMvcInstance()->getView();
		$view->navigation()->setAcl($acl)->setRole($role);
		$this->view = $view;
		$view->addHelperPath("Ig/View/Helper","Ig_View_Helper");
		$this->_loadConfig();
		
		//read any flash message if sent!!!
		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->notificationMessages = $flashMessenger->getMessages();
	}
	
	/**
	 * Method to override the template
	 */
	function _setCustomTemplate($folder)
	{
		  $view = Zend_Layout::getMvcInstance();
		  $view->setLayoutPath(array('layoutPath' => BASE_PATH . "/layouts/$folder/"));
	}
	
	function _loadUser()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) 
		{
			$this->user = $this->view->user = $auth->getIdentity();
		}
	}
	
	function _setCustomView($file)
	{
		$this->_helper->viewRenderer->setRender($file);
	}
	
	/** 
	 *  Redirect with a status message!
	 */
	function _redirect($url, $message='', array $options=array())
	{
		if($message != '')
		{
			$this->_helper->FlashMessenger($message);	
		}
		$this->_helper->redirector->gotoUrl($url, $options);
	}
	
// 	function _checkAuth($redirect = '/index', $message = 'Not Authorized to view this Resource' ,$role='',$accesspoint='' )
	function _checkAuth($accesspoint = '',$action = NULL, $redirect = '/index', $message = 'Not Authorized to view this Resource')
	{
		$role = isset($this->user->role)?$this->user->role:'Guests';
		
		if($accesspoint == '')
		{
			$module =  $this->_getParam('module');
			$controller =  $this->_getParam('controller');
			$action = $this->_getParam('action');
			$accesspoint = "$module:$controller";
		}
		
		$acl = Zend_Registry::get("ACL");

		if($acl->has($accesspoint)){
			if($action == NULL)
				$access = $acl->isAllowed($role,$accesspoint);
			else
				$access = $acl->isAllowed($role,$accesspoint,$action);
		}else{
			if($role == 'Superadmins') $access = true;
		}
		
		if(!$access){
  // echo $access . $accesspoint . $action;
			$this->_redirect($redirect,$message);
		}
	}
	
	/**
	 * returns -1 if accesspoint not defined
	 * returns 1 if allowed
	 * returns 0 if not allowed
	 */
	function _isAllowed($role,$accesspoint,$action = NULL)
	{
		$acl = Zend_Registry::get("ACL");
		if(!$acl->has($accesspoint))
			return '-1';
		else
			if($action == NULL)
				return $acl->isAllowed($role,$accesspoint);
			else
				return $acl->isAllowed($role,$accesspoint,$action);
	}
	
	function _setAccess($role,$accesspoint,$permission = 'allow')
	{
		$acl = Zend_Registry::get("ACL");
		if(!$acl->has($accesspoint))  
			$acl->add(new Zend_Acl_Resource($accesspoint));
		
		$acl->$permission($role,$accesspoint);
	}
	
	function _loadConfig()
	{
		$cfg = new Config();
		$this->view->headTitle()->setSeparator(' :: ');
		$this->view->headTitle($cfg->get('SITE_TITLE'));
		$this->view->headMeta()->appendName('keywords',$cfg->get('SITE_KEYWORDS'));
		$this->view->headMeta()->appendName('description', $cfg->get('SITE_DESC'));
	}
	
}