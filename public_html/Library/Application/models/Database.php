<?php

Class Database
{
	function __construct()
	{
		$this->db = Zend_Registry::get('db');
	}
	
	public function translate($str)
	{
		return Zend_View_Helper_Translate::translate($str);
	}
}
