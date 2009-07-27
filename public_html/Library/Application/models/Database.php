<?php

Class Database
{
	function __construct()
	{
		$this->db = Zend_Registry::get('db');
	}
}
