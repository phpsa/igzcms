<?php

class Ig_Form extends Zend_Form
{
	public function __construct($options = null)
	{
		$this->addPrefixPath('Ig_Form_', 'Ig/Form/');
		parent::__construct($options);
	}
}
