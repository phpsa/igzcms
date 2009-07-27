<?php

class User extends Database
{
	
	function update($data,$id)
	{
		return $this->db->update('users', $data, "id =  $id");
	}
	
	
}