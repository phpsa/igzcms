<?php

Class Modules extends Database
{
	
	function listModules($page = 0)
	{
		$selection = $this->db->select()->from("modules")->order('isCore desc')->order('moduleName');
		$paginator = Zend_Paginator::factory($selection);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(25);
		$paginator->setPageRange(10);
		return $paginator;
	}
	
}
