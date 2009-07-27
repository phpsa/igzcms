<?php

class Config extends Database
{
	function get($cfgkey)
	{
		return $this->db->fetchOne("Select cfgval from siteconfig where cfgkey = '$cfgkey'");
	}
}//cfgkey 	cfgval