<?php

class Ig_View_Helper_AccessSelect
{
	private $list = array();
	
	function accessSelect($selected = '')
	{
		$acl = new Acl();
		$roles = $acl->getRoles();
		$this->_builder($roles,0);
		$xhtml = '<Select name="role">';
		foreach($this->list as $role)
		{
			$sel = ($role == $selected)?'Selected':'';
			$xhtml .= '<option value="'.$role.'" ' . $sel . '>'.$role.'</option>';
		}
		$xhtml .= '</select>';
		return $xhtml;
		//print_r($this->list);
	}
	
	function _builder($arr, $parent = 0, $indent = '')
	{
		foreach($arr as $row)
		{
			if($row['parent_id'] == $parent)
			{
				$this->list[$row['id']] = $indent . $row['role'];
				$this->_builder($arr,$row['id'],$indent.'-',$list);
			}
		}
	}
	
}