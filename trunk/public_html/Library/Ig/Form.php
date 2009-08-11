<?php

class Ig_Form extends ZendX_JQuery_Form
{
	public function __construct($options = null)
	{
		$this->addPrefixPath('Ig_Form_', 'Ig/Form/');
		parent::__construct($options);
	}
	
	public function translate($str)
	{
		return Zend_View_Helper_Translate::translate($str);
	}
}
