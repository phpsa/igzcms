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
	
	/*function getMenuItemList($menuid, $page=0)
	{
		$selection = $this->db->select()->from("menuitems")->where('menu_id=?',$menuid)->order('ordering');
		$paginator = Zend_Paginator::factory($selection);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(25);
		$paginator->setPageRange(10);
		return $paginator;
	}*/
	
	public function getMenuItemList($menuid,$page=0)
	{
		
		$result = $this->db->fetchAll("SELECT * FROM menuitems where menu_id='$menuid' order by parent_id asc, ordering asc, label asc");
		//print_r($result);
		$children = array();
		
		if ( $result )
		{
			// first pass - collect children
			foreach ( $result as $v )
			{
				$pt 	= $v['parent_id'];
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		
		// second pass - get an indent list of the items
		$items = $this->treerecurse( 0, '', array(), $children, 9999, 0, 1 );
		
		// third pass, set into different menus
		foreach($items as $key=>$item)
		{
			$menulist[$key] = $item;
		}
	//	print_r($menulist);
	//	die();
		$paginator = Zend_Paginator::factory($menulist);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(25);
		$paginator->setPageRange(10);
		return $paginator;
		
		//return $menulist;
	}
	
	private function treerecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 )
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v['id'];
				
				if ( $type ) 
				{
					$pre 	= '<sup>|_</sup>&nbsp;';
					$spacer = '.&nbsp;&nbsp;&nbsp;';
				} else {
					$pre 	= '- ';
					$spacer = '&nbsp;&nbsp;';
				}
				
				if ( $v['parent_id'] == 0 ) 
				{
					$txt 	= $v['label'];
				} else {
					$txt 	= $pre . $v['label'];
				}
				$pt = $v['parent_id'];
				$list[$id] = $v;
				$list[$id]['treename'] = "$indent$txt";
				$list[$id]['children'] = count( @$children[$id] );
				$list = $this->treerecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
			}
		}
		return $list;
	}
	
	function updateMenuOrder($items)
	{
		$data = array();
		foreach($items as $item=>$value)
		{
			$data['ordering'] = $value;
			$this->db->update('menuitems', $data, "id='$item'");
			
		}
	}
	
}

