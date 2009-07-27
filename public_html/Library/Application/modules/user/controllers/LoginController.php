<?php

class User_LoginController extends Ig_Controller_Action
{
	
	public function indexAction()
	{
		
		if ($this->getRequest()->isPost()) 
		{
			$userDB = new User;
			$values = $_POST;
			$adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
			$adapter->setTableName('users');
			$adapter->setIdentityColumn('username');
			$adapter->setCredentialColumn('password');
			$adapter->setIdentity($values['username']);
			$adapter->setCredential(hash('MD5',$values['password']));
			
			$auth=Zend_Auth::getInstance();
			$result = $auth->authenticate($adapter);
			if($result->isValid())
			{
				$auth->getStorage()->write($adapter->getResultRowObject(null,'password'));
				$user = $auth->getIdentity();
				if($user->active == 0)
				{
					$auth->clearIdentity();
					$this->view->notificationMessages[0] = 'Your account is Suspended';
					$this->view->failedAuthentication = true;
				}else{
					$data = array('moddate' => DATE("Y-m-d H:i:s"));
					//$where = $values['username'];
					
					$userDB->update($data, $user->id);
					
					$this->_redirect('/index');
				}
			}else{
				$this->view->notificationMessages[0] = 'Username password combination not found';
			}
		}
	}
	
}

