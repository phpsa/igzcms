<?php

class Ig_View_Helper_DrawBlock extends Zend_View_Helper_Abstract 
{
	public $params;
	
	public function __construct()
	{
		$front   = Zend_Controller_Front::getInstance(); 
		$modules = $front->getControllerDirectory();
		if (empty($modules)) 
		{
		require_once 'Zend/View/Exception.php';
		throw new Zend_View_Exception('DrawBlock helper depends on valid front controller instance');
		}
	
		$request  = $front->getRequest(); 
		$response = $front->getResponse(); 
	
		if (empty($request) || empty($response)) {
			require_once 'Zend/View/Exception.php';
			throw new Zend_View_Exception('DrawBlock view helper requires both a registered request and response object in the front controller instance');
		}
		
		$auth = Zend_Auth::getInstance();
	
	
		$this->params->request       = clone $request;
		$this->params->response      = clone $response;
		$this->params->dispatcher    = clone $front->getDispatcher(); 
		$this->params->defaultModule = $front->getDefaultModule();
		$this->params->acl = Zend_Registry::get("ACL");
		$this->params->user = $auth->getIdentity();
		$this->db = Zend_Registry::get("db");
		
	}
	
	function drawBlock($pos, $tags = array() ,$getCount = false)
	{
		$blocks = $this->db->fetchAll("Select b.* from blocks as b left join block_positions as bp on b.block_position_id = bp.id where bp.blockName = '$pos' and b.published = 1 order by ordering asc");
		
		if($getCount)
		{
			return count($blocks);
		}
		if(!isset($tags[0])) $tags = array(0=>'',1=>'');
		
		$xhtml = '<div id="' . strtolower($pos) . '_block_container">';
		foreach($blocks as $block)
		{
			//block_title 	show_title
			$xhtml .= $tags[0];
			if($block['show_title'] == 1) {
			$xhtml .= '<h2>' . $block['block_title'] . '</h2>';
			}
			$params = $this->params;
			$params->params = $block['params'];
			$class = new $block['ClassFile']();
			$xhtml .= $class->drawBlock($params, $pos);
			$xhtml .= $tags[1];
		}
		$xhtml .= '</div>';
		
		return $xhtml;
	}
}