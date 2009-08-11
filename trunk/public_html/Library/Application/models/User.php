<?php

class User extends Database
{
	
	function update($data,$id = 0)
	{
		if($id = 0)
		{
			$id = $data['id'];
		}
		return $this->db->update('users', $data, "id =  $id");
	}
	
	public function listUsers($page=0)
	{
		$selection = $this->db->select()->from("users")->order('username');
		$paginator = Zend_Paginator::factory($selection);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(25);
		$paginator->setPageRange(10);
		return $paginator;
	}
	
	function getUserByUsername($username)
	{
		$username = addslashes($username);
		return $this->db->fetchRow("select * from users where username='$username' or email='$username'");
	}
	
	function testUniqueEmail($email)
	{
		$result =  $this->db->fetchOne("Select count(email) as ecnt from users where email='" . addslashes($email) . "'");
		return $result;
	}
	
	function testUniqueUsername($email)
	{
		return $this->db->fetchOne("Select count(username) as ecnt from users where username='" . addslashes($email) . "'");
	}
	
	function register($data)
	{
		//lets set our real insert data array
		$user = array(
			'name' => addslashes($data['name']),
			'username' => addslashes($data['username']),
			'email'	=> addslashes($data['email']),
			'password' => md5($data['password']),
			'active' => '0',
			'role'	=> 'Members',
			'createdate' => DATE("Y-m-d H:i:s"));
			$activationcode = substr(md5($data['email']), 0, 8);
			$user['activation'] = $activationcode;
		
		if($this->db->insert('users', $user))
		{
			return $activationcode;
		}else{
			return false;
		}
		//return $activationcode;
	}
}