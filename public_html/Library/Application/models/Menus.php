<?php

class Menus extends Database
{
	
	function getMenus()
	{
		return $this->db->fetchall("select * from menus");
	}
	
	function getMenuItems($parent_id = 0, $menuid = 0)
	{
		return $this->db->fetchAll("Select * from menuitems where parent_id = '$parent_id' and menu_id = '$menuid'");
	}
}