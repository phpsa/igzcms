<?php

Class Ig_Controller_Admin extends Ig_Controller_Action
{
	public function init()
	{
		$this->_setCustomTemplate('admin');
		parent::init();
	}
	
	function _addButton($link, $title, $css = 'buttongeneral')
	{
		$this->view->moduleActions .= '<a href="' . $link . '"><div  class="adminbutton ' . $css . '">' . $title . '</div></a>';
	}
	
	function _addSubmit($title = 'Save', $css = 'buttonsave')
	{
		$link = 'javascript:document.adminForm.submit();';
		$this->_addButton($link,$title,$css);
	}
	
}