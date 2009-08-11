<?php

class Ig_View_Helper_BbcodeEditor extends Zend_View_Helper_FormTextarea
{
	protected $_defaultScript = '/scripts/bbeditor/ed.js';
	
	public function setDefaultScriptPath($path)
	{
		$this->_defaultScript = $path;
	}
	
	public function bbcodeEditor($name, $value = null, $attribs = null)
	{
		$this->view->headScript()->appendFile($this->_defaultScript);
		$xhtml = '<script>edToolbar("' . $name . '");</script>';
		$xhtml .= $this->formTextarea($name,$value,$attribs);
		return $xhtml;
	}
}