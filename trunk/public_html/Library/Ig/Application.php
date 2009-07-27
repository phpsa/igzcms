<?php

class Ig_Application
{
	
	private $configPath;
	private $cmods = array();
	
	public function __construct()
	{
		set_include_path('.' . PATH_SEPARATOR . LIBRARY_PATH . '/Application/models'  . PATH_SEPARATOR  . get_include_path()); //block1
	}
	
	public function setEnvironment($environment)
	{
		$this->_environment = $environment;
	}
	
	/**
	* Returns the environment which is currently set
	*
	* @return string
	*/
	public function getEnvironment()
	{
		return $this->_environment;
	}
	
	
	public function bootstrap($configPath='')
	{
		if (!$this->_environment) {
			throw new Exception('Please set the environment using ::setEnvironment');
		}
		
		$this->configPath = $configPath;
		if($configPath == '' || !file_exists($configPath))
		{
			throw new Exception('Please supply the configuration file path');
		}
		
		$frontController = $this->initialize();
		
		$this->setupRoutes($frontController);
		$response = $this->dispatch($frontController);
		
		$this->render($response);
	}
	
	public function initialize()
	{

		require_once('Zend/Loader/Autoloader.php');
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);

		$config = new Zend_Config_Ini($this->configPath, $this->getEnvironment());
		
		$cache = $this->runcache($config);
		$this->cache = $cache;
		Zend_Registry::set('config', $config);
		Zend_Registry::set('cache', $cache);
		$plugs = $config->plugs;
		if($plugs){
			foreach($plugs as $key=>$value)
			{
				Zend_Registry::set($key, $value);
			}
		}

		$frontController = Zend_Controller_Front::getInstance();
		$frontController->throwExceptions((bool) $config->mvc->exceptions);
   
		/**
		* Set our include path for all modules that are loaded!!! NB
		*/
		
		$sitefolder = $config->site;
		if($sitefolder->identifier != '')
		{
			$custommoduleDir = LIBRARY_PATH . '/Sites/' . $sitefolder->identifier . '/modules';
			$this->loadModules($frontController, $custommoduleDir);
		}
		
		$moduleFolder = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules';
		$this->loadModules($frontController, $moduleFolder);

		$router = $frontController->getRouter();
		$router->addDefaultRoutes();
		
		/**
		*ADD The database to the registry!!!
		*/
		
		$db = Zend_Db::factory($config->db);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
		
		Zend_Registry::set('baseUrl', $config->baseUrl);
		date_default_timezone_set($config->date_default_timezone);
		defined('APPLICATION_ENVIRONMENT')
			|| define('APPLICATION_ENVIRONMENT', $this->getEnvironment());
   
		$layoutOptions = array('layoutPath' => BASE_PATH . '/layouts/',
								'layout'	 => 'layout' // our default layout
								);

		Zend_Layout::startMvc($layoutOptions);
		$view = new Zend_View();
		
		$acl = $this->setAcl();
		Zend_Registry::set('ACL', $acl);
		
		$menu = $this->generateMenus();
		$container = new Zend_Navigation($menu);
		Zend_Registry::set('Zend_Navigation', $container);

  // Zend_Layout::setLayoutPath(array('layoutPath' => BASE_PATH . '/layouts/admin/'));
 //  $view = Zend_Layout::getMvcInstance();
  // $view->setLayoutPath(array('layoutPath' => BASE_PATH . '/layouts/admin/'));
		return $frontController;
	}
	
	
	public function runcache($config)
	{
		$life = (int)$config->cache->lifetime;
		
		$frontendOptions = array(
		'lifetime' => $life,
		'debug_header' => $config->cache->debug_header, // for debugging
		'regexps' => array(
		'^/$' => array('cache' => true),
						   '^/index/' => array('cache' => true),
						   )
						   );
						   
						   $backendOptions = array(
						   'cache_dir' => BASE_PATH . '/cache/'
						   );
						   
						   $cache = Zend_Cache::factory('Page',
						   'File',
						   $frontendOptions,
						   $backendOptions);
						   $cache->start();				
						   return $cache;
						   
	}
	
	public function setupRoutes(Zend_Controller_Front $frontController)
	{
		
		// Retrieve the router from the frontcontroller 
		$router = $frontController->getRouter();
		$router->addRoute('', new Zend_Controller_Router_Route('default'));
		return $router;
	}
	
	public function dispatch(Zend_Controller_Front $frontController)
	{
		// Return the response
		$frontController->returnResponse(true);
		return $frontController->dispatch();
	}
	
	/**
	* Renders the response
	*
	* @param  object Zend_Controller_Response_Abstract $response - The response object
	* @return void
	*/
	public function render(Zend_Controller_Response_Abstract $response)
	{
		$response->sendHeaders();
		$response->outputBody();
	}
	
	function generateMenus()
	{
		$acl = Zend_Registry::get("ACL");
		$menu_model = new Menus();
		$menus = $menu_model->getMenus();
		foreach($menus as $menu)
		{
			$menuBuilderArray[$menu['id']] = array('label' => $menu['menuname'],'title'=>$menu['menuname'], 'controller'=>'index', 
			'pages' => $this->_menuGenerate($menu_model,0,$menu['id']));
			//$menuBuilderArray[$item['menu_id']]['pages'] = $this->_menuGenerate($menu_model,0,array());
		}
		return $menuBuilderArray;
	}
	
	private function _menuGenerate($menu_model, $parent_id, $menuid)
	{
		$acl = Zend_Registry::get('ACL');
		$items = $menu_model->getMenuItems($parent_id, $menuid);
		$menuArray = array();
		foreach($items as $item)
		{
			$acl->add(new Zend_Acl_Resource('menuitem:'.$item['id']));
			$acl->allow($item['role'],'menuitem:'.$item['id']);
			
			$params = array();
			if($item['params'] != '' && $item['module'] != '')
			{
				if($item['params'][0] == '/') $item['params'] =  substr($item['params'], 1);
				$exploded = explode("/", $item['params']);
				$paramcnt = count($exploded);
				$x = 0;
				while($x <= $paramcnt)
				{
					$params[$exploded[$x]] = $exploded[$x+1];
					$x = $x+2;
				}
			}
			
			if($item['module'] == 'default' && $item['controller'] == 'page')
			{
				$tlabel = str_replace(" ","_", $item['label']);
				$params['p'] = preg_replace("/[^a-zA-Z0-9_\d]/i", "",$tlabel);
			}
			if($item['module'] != '')
			{
				$menuArray[$item['id']] = array(
					'label' => $item['label'], 
					'title'=>$item['label'], 
					'module'=>$item['module'],
					'controller'=>$item['controller'],
					'action'=>$item['action'],
					'params'=> $params,
					'resource'   => 'menuitem:'.$item['id'],
					'pages'=>$this->_menuGenerate($menu_model, $item['id'],$menuid));

			}else{
				$menuArray[$item['id']] = array(
					'label' => $item['label'],
					'title'	=> $item['title'],
					'uri'	=> $item['params'],
					'resource'   => 'menuitem:'.$item['id'],
					'pages'=>$this->_menuGenerate($menu_model, $item['id'],$menuid));
			
			}
		}
		return $menuArray;

	}
	
	function setAcl()
	{
		$acl_model = new Acl();
		$roles = $acl_model->getRoles();
		foreach($roles as $role)
		{
			$acl_named[$role['id']] = $role['role'];
		}
		$acl = new Zend_Acl();
		foreach($roles as $role)
		{
			if($role['parent_id'] > 0)
			{
				$acl->addRole(new Zend_Acl_Role($role['role'],$acl_named[$role['parent_id']]));
			}else{
				$acl->addRole(new Zend_Acl_Role($role['role']));
			}
			$acl->add(new Zend_Acl_Resource($role['role']));
			$acl->allow($role['role'], $role['role']);
		}
		
		//Ok lets load our settings
		$access = $acl_model->getAccess();
		
		
		
		foreach($access as $acc)
		{
			if(!$acl->has($acc['controller']))
			{
				$acl->add(new Zend_Acl_Resource($acc['controller']));
			//	echo "NEW ACL {$acc['controller']}<br />";
			}
		//	if($acc['action'] == NULL || $acc['action'] == 'NULL')
		//	{
		//		echo $acc['controller'];;
		//		$acl->allow($acc['role'],$acc['controller']);
		//	}else{
		if($acc['role'] != 'Guests')
		{
			$acl->deny('Guests',$acc['controller'],$acc['action']);
		}
				$acl->allow($acc['role'],$acc['controller'],$acc['action']);
		//		echo "{$acc['role']},{$acc['controller']},{$acc['action']}<br/>";
		//	}
			
		}
		$acl->allow('Superadmins');
		return $acl;
	}
	
	function loadModules($frontController, $folder)
	{
		if(is_dir($folder))
		{
			$custommodules = scandir($folder);
		}
				
		if(is_array($custommodules))
		{
			foreach($custommodules as $module)
			{
				if($module[0] == '.') continue;
				if(!in_array($module, $this->cmods))
				{
					$frontController->addControllerDirectory($folder . '/' . $module . '/controllers', $module);
					$this->cmods[] = $module;
				}
				set_include_path($folder
									. DIRECTORY_SEPARATOR . $module
									. PATH_SEPARATOR . get_include_path());
				set_include_path($folder
									. DIRECTORY_SEPARATOR . $module
									. DIRECTORY_SEPARATOR .  'views'
									. PATH_SEPARATOR . get_include_path());
				set_include_path($folder
									. DIRECTORY_SEPARATOR . $module
									. DIRECTORY_SEPARATOR . 'models'
									. PATH_SEPARATOR . get_include_path());
			}
		}
	}
}
