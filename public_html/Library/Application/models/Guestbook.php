<?php

class Guestbook extends Database
{
	
	function getAll()
	{
		print_r($this->db->fetchAll("Select * from guestbook"));
	}
}