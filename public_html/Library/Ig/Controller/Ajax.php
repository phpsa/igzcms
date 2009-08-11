<?php

Class Ig_Controller_Ajax extends Ig_Controller_Action
{
	public function init()
	{
		$this->_setCustomTemplate('blank');
		parent::init();
	}
}