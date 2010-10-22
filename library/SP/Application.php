<?php
/***********************************************************************************************************
Copyright 2010 VictorLi (luckylzs@gmail.com). All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are
permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, this list of
      conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright notice, this list
      of conditions and the following disclaimer in the documentation and/or other materials
      provided with the distribution.

THIS SOFTWARE IS PROVIDED BY VictorLi (luckylzs@gmail.com) ``AS IS'' AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL VictorLi (luckylzs@gmail.com) OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those of the
authors and should not be interpreted as representing official policies, either expressed
or implied, of VictorLi (luckylzs@gmail.com).
***********************************************************************************************************/

class SP_Application{
	/**
	 * @var Zend_Auth_Adapter_DbTable
	 */
	protected static $_authAdapter = null;
	/**
	 * 
	 * @var Zend_Acl
	 */
	protected static $_acl = null;
	/**
	 * @var Zend_Cache
	 */
	protected static $_cache = null;
	/**
	 * @var Zend_Log
	 */
	protected static $_log = null;
	/**
	 * 
	 * @var Zend_Db
	 */
	protected static $_db = null;
	/**
	 * 
	 * @var Zend_View
	 */
	protected static $_view = null;
	/**
	 * 
	 * @var Zend_Controller_Router_Rewrite
	 */
	protected static $_router = null;
	/**
	 * 
	 * @var string
	 */
	private static $_env = 'development';
	/**
	 * 
	 * @var string
	 */
	private static $_cnf_file = null;
	/**
	 * 
	 * @var Zend_Config_Ini
	 */
	protected static $_config = null;
	/**
	 * 
	 * @var Zend_Layout
	 */
	private static $_layout = null;
	/**
	 * 
	 * @var Zend_Translate
	 */
	protected static $_translate = null; 
	/**
	 * 
	 * @var Zend_Loader_Autoloader_Resource
	 */
	protected static $_autoloader = null;
	/**
	 * Application construct
	 * @param string $env
	 * @param string $file
	 */
	public function __construct($env,$file){
		self::$_env = $env;
		self::$_cnf_file = $file;
	}
	
	protected function _init(){
		
		set_include_path(implode(PATH_SEPARATOR,array(SP_LIB_PATH,SP_APP_PATH . DIRECTORY_SEPARATOR . 'modules',get_include_path(),)));
		
		require_once 'Zend/Loader/Autoloader.php';
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(false);
		$autoloader->registerNamespace('SP_');
		$autoloader->registerNamespace('Apache_');//for solr
		
		try {
            Zend_Session::start();
        } catch (Zend_Session_Exception $error) {
            Zend_Session::writeClose();
            Zend_Session::start();
            Zend_Session::regenerateId();
            trigger_error($error->getMessage());
        }
		self::_setAutoloader();
		self::_setConfig();
		self::_setView();
		self::_setRouter();
		self::_setDbAdapter();
		self::_setLog();
		self::_setCache();
		self::_setACL();
		self::_setAuthAdapter();
		self::_setLayout();
		self::_setTranslate();
		self::_setActionHelper();
		
		$front = Zend_Controller_Front::getInstance();
		$front->setRouter(self::$_router)
			  ->addModuleDirectory(SP_APP_PATH . '/modules')
			  ->setDefaultModule('default')
			  ->setModuleControllerDirectoryName('controllers')
			  ->throwExceptions(true)
			  ->returnResponse(false)
			  ->setDefaultControllerName('Index')
			  ->setDefaultAction('index')
			  ->setParam('prefixDefaultModule',true)
			  ->setParam('noViewRenderer',true)
			  ->setParam('useDefaultControllerAlways',false);
		
		//register some useful plugins
		$front->registerPlugin(new SP_Controller_Plugin_AppPlugin());
		
		//set default db table adapter
		Zend_Db_Table::setDefaultAdapter(self::$_db);
		//set default translator for Zend_Form
		Zend_Form::setDefaultTranslator(self::$_translate);
	}
	
	public function run(){
		try{
			$this->_init();
			$front = Zend_Controller_Front::getInstance();
			$front->dispatch();
		}catch(Zend_Controller_Exception $e){
			trigger_error($e->getMessage());
			exit;
		}
	}
	/**
	 * set Resource auto loader
	 */
	public function _setAutoloader(){
		self::$_autoloader = new Zend_Loader_Autoloader_Resource(array('basePath'=>SP_APP_PATH . '/modules','namespace'=>'SP_'));
		self::$_autoloader->addResourceTypes(array(
						  	'user'=>array(
						  		'path'=>'user/models',
						  		'namespace'=>'User_Model',
						  	),
						  	'project'=>array(
						  		'path'=>'project/models',
						  		'namespace'=>'Project_Model'
						  	)
						  ));
	}
	/**
	 * set configuration
	 */
	protected function _setConfig(){
		if(null === self::$_config){
			if(file_exists(self::$_cnf_file)){
				try{
					self::$_config = new Zend_Config_Ini(self::$_cnf_file,self::$_env);
				}catch(Zend_Config_Exception $e){
					trigger_error($e->getMessage());
					exit;
				}
			}
		}
	}
	/**
	 * set auth adapter
	 */
	protected function _setAuthAdapter(){
		self::$_authAdapter = new Zend_Auth_Adapter_DbTable(self::$_db);
		self::$_authAdapter->setTableName('users')
						   ->setIdentityColumn('name')
						   ->setCredentialColumn('pwd');
						   
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('SP_-Auth'));
	}
	/**
	 * set acl
	 */
	protected function _setACL(){
		self::$_acl = new Zend_Acl();
		self::$_acl->addRole(new Zend_Acl_Role('guest'));
		self::$_acl->addRole(new Zend_Acl_Role('member'),'guest');
		self::$_acl->addRole(new Zend_Acl_Role('admin'),'member');
	}
	/**
	 * set view
	 */
	protected function _setView(){
		if(null === self::$_view){
			self::$_view = new Zend_View();
			$title = "Shrimp Project";
			
			self::$_view->setScriptPath(SP_APP_PATH . '/modules/default/views')
						->setEncoding('UTF-8')
				 		->strictVars(false)
				 		->addHelperPath('SP/View/Helper','SP_View_Helper_');
				 		
			self::$_view->doctype('XHTML1_STRICT');
			self::$_view->headTitle($title);
			self::$_view->headLink()->appendStylesheet('/theme/default/main.css');
			self::$_view->headScript()->appendFile('/js/jquery-1.4.2.min.js');
			self::$_view->headScript()->appendFile('/js/jquery.cookie.js');
			self::$_view->headScript()->appendFile('/js/sorttable.js');
			//self::$_view->htmlTable();
		}
	}
	/**
	 * set router
	 */
	protected function _setRouter(){
		if(null === self::$_router){
			self::$_router = new Zend_Controller_Router_Rewrite();
			
			$route_project = new Zend_Controller_Router_Route_Regex(
				'index.php/project/(\d+)',
				array(
					'controller' 	=> 	'project',
					'action'		=>	'view',
				),
				array(
					1	=>	'id',
				)
			);
			
			$route_project_list = new Zend_Controller_Router_Route_Regex(
				'project/list/(\d+)',
				array(
					'controller'=>'Index',
					'action'=>'list',
					'module'=>'project'
				),
				array(1=>'page'),
				'project/list/%d'
			);
			
			$route_user_login = new Zend_Controller_Router_Route_Regex(
				'user/login',
				array(
					'module'=>'user',
					'controller'=>'index',
					'action'=>'login'
				)
			);
			
			$route_user_logout = new Zend_Controller_Router_Route_Regex(
				'user/logout',
				array(
					'module'=>'user',
					'controller'=>'index',
					'action'=>'logout'
				)
			);
			
			$route_user_list = new Zend_Controller_Router_Route_Regex(
				'user/list/(\d+)',
				array(
					'module'=>'user',
					'controller'=>'Index',
					'action'=>'list',
				),
				array( 1 => 'page'),
				'user/list/%d'
			);
			
			$route_user_edit = new Zend_Controller_Router_Route_Regex(
				'user/edit',
				array(
					'module'=>'user',
					'controller'=>'index',
					'action'=>'edit',
				)
			);
			
			$route_user_edit_id = new Zend_Controller_Router_Route_Regex(
				'user/edit/(\d+)',
				array(
					'module'=>'user',
					'controller'=>'index',
					'action'=>'edit',
				),
				array(
					1=>'id'
				)
			);
			
			$route_user_save = new Zend_Controller_Router_Route_Regex(
				'user/save',
				array(
					'module'=>'user',
					'controller'=>'index',
					'action'=>'save',
				)
			);
			
			self::$_router->addRoutes(array(
						$route_project,
						//$route_project_list,
						$route_user_login,
						$route_user_logout,
						$route_user_list,
						$route_user_edit,
						$route_user_edit_id,
						$route_user_save));
		}
	}
	/**
	 * set logger
	 */
	protected function _setLog(){
		if(null === self::$_log){
			self::$_log = new SP_Log();
			self::$_log->setEventItem('pid',getmypid());
			
			$format = '%timestamp% pid(%pid%) %priorityName% (%priority%): %message%' . PHP_EOL;
			$formatter = new Zend_Log_Formatter_Simple($format);
			
			$log_file = SP_APP_PATH . DIRECTORY_SEPARATOR . 'log/log.txt';
			$writer_file = new Zend_Log_Writer_Stream($log_file);
			$writer_file->setFormatter($formatter);
			$writer_firebug = new Zend_Log_Writer_Firebug();
			
			self::$_log->addWriter($writer_file);
			self::$_log->addWriter($writer_firebug);
			
			self::$_log ->addFilter(new Zend_Log_Filter_Priority(Zend_Log::INFO));
		}
		
		Zend_Registry::set('logger',self::$_log);
	}
	/**
	 * set cache
	 */
	protected function _setCache(){
		if(null === self::$_cache){
			$frontendOptions = array(
				'lifeTime'=>3600,
				'automatic_serialization'=>true,
			);
			
			$backendOptions = array(
				'cache_dir' => SP_APP_PATH . '/cache/',
			);
			
			self::$_cache = Zend_Cache::factory('Core','File',$frontendOptions,$backendOptions);
		}
	}
	
	protected function _setDbAdapter(){
		if(null === self::$_db){
			try{
				self::$_db = Zend_Db::factory(self::$_config->db->adapter,self::$_config->db->params->toArray());
			}catch(Zend_Db_Exception $e){
				trigger_error($e->getMessage());
			}
		}
	}
	
	protected function _setLayout(){
		if(null === self::$_layout){
			self::$_layout = Zend_Layout::startMvc();
			self::$_layout->setLayoutPath(SP_APP_PATH . DIRECTORY_SEPARATOR . 'layouts')
						  ->setLayout('layout');
		}
	}
	/**
	 * setting custom prefix and register need action helpers
	 */
	protected function _setActionHelper(){
		Zend_Controller_Action_HelperBroker::addPrefix('SP_Controller_Action_Helper');
		Zend_Controller_Action_HelperBroker::getStaticHelper('Paginator');
	}
	
	protected function _setTranslate(){
		self::$_translate = new Zend_Translate('gettext',SP_APP_PATH . DIRECTORY_SEPARATOR . 'locale','en',array('disableNotices'=>true,'scan'=>Zend_Translate::LOCALE_DIRECTORY));
		//log untranslated string
		//self::$_translate->setOptions(array('log'=>self::$_log,'logUntranslated'=>true));
		self::$_translate->setCache(self::$_cache);
		Zend_Registry::getInstance()->set('Zend_Translate',self::$_translate);
		
	}
	/**
	 * get Zend_Auth_Adapter
	 * 
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public static function getAuthAdapter(){
		return self::$_authAdapter;
	}
	/**
	 * get Zend_Acl instance
	 * 
	 * @return Zend_Acl
	 */
	public static function getAcl(){
		return self::$_acl;
	}
	/**
     * get Zend_View object
     *
     * @return Zend_View
     */
	public static function getView() {
		return self::$_view;
	}
	/**
	 * get Zend_Controller_Router_Abstract object
	 * 
	 * @return Zend_Controller_Router_Abstract
	 */
	public static function getRouter(){
		return self::$_router;
	}
	/**
	 * get global logger
	 * 
	 * @return Zend_Log
	 */
	public static function getLogger(){
		return self::$_log;
	}
	/**
	 * get Zend_Cache object
	 * 
	 * @return Zend_Cache
	 */
	public static function getCache(){
		return self::$_cache;
	}
	/**
	 * get Zend_Db adapter
	 * 
	 * @return Zend_Db
	 */
	public static function getDb(){
		return self::$_db;
	}
	/**
	 * get environment
	 * 
	 * @return string
	 */
	public static function getEnvironment(){
		return self::$_env;
	}
	/**
	 * get Zend_Config_Ini object
	 * 
	 * @return Zend_Config_Ini
	 */
	public static function getConfig(){
		return self::$_config;
	}
	/**
	 * get Zend_Layout instance
	 * @param $name
	 * 
	 * @return Zend_Layout
	 */
	public static function getLayout($name){
		if(!is_null($name)){
			self::$_layout->setLayout($name);
		}
		
		return self::$_layout;
	}
}

?>