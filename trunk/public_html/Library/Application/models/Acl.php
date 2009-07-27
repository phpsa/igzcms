<?php

class Acl extends Database
{
	
	
	function getRoles()
	{
		return $this->db->fetchAll("Select * from acl_roles order by parent_id asc");
	}
	
	function getAccess()
	{
		return $this->db->fetchAll("Select * from acl_access");
	}
	
	
	
}
