<?php

class Block_Search 
{
	public function __construct()
	{
	}
	
	public function drawBlock($params='', $block='')
	{
		$xhtml = '<form action="/search" method="Post">
			<fieldset><input id="search-text" type="text" size="15" name="s" /><input id="search-submit" type="submit" value="Search" /></fieldset></form>';
		return $xhtml;
	}
	
	
}