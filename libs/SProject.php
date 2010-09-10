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
class SProject{
	
	protected static $config = null;
	protected static $cache = null;
	protected static $db = null;
	protected static $instance = null;
	
	private function __construct(){}
	
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new SProject();	
			self::$instance->init();
			self::$instance->registryGlobals();
		}
		
		return self::$instance;
	}
	
	public function run(){
		try{
			$front = Zend_Controller_Front::getInstance();
			$front->dispatch();
		}catch(Zend_Controller_Exception $e){
			trigger_error($e->getMessage(),$e->getCode());
			die('Error to run the application');
		}
	}
	
	protected function init(){
		
		//set include path
		set_include_path('.' . PATH_SEPARATOR 
						. SP_LIB_PATH 
						. PATH_SEPARATOR
						. SP_CORE_PATH
						. PATH_SEPARATOR
						. get_include_path()
						);
		
		require_once 'Zend/Loader/Autoloader.php';				
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('SP_');
		
		try{
			Zend_Session::start();
		}catch(Zend_Session_Exception $e){
			trigger_error($e->getMessage(),$e->getCode());
		}
		
		//set Zend_View
		$view = new Zend_View(array('encoding'=>'UTF-8'));
		$view->setScriptPath(SP_CORE_PATH . DIRECTORY_SEPARATOR .'default'. DIRECTORY_SEPARATOR .'views');
		
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
		$viewRenderer->setViewBasePathSpec(':moduleDir/views')
					 ->setViewScriptPathNoControllerSpec(':action.:suffix')
					 ->setViewScriptPathSpec(':controller/:action.:suffix')
					 ->setViewSuffix('php');
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        
		//set plugin
		$appPlugin = new SP_Controller_Plugin_AppPlugin();
		
        //set Zend_Controller_Front
        $front = Zend_Controller_Front::getInstance();
        $front->setDefaultModule('default')
        	  ->setDefaultControllerName('Index')
        	  ->setDefaultAction('index')
        	  ->setModuleControllerDirectoryName('controllers')
        	  ->setControllerDirectory(SP_CORE_PATH . '/default/controllers','default')
        	  ->addModuleDirectory(SP_CORE_PATH)
        	  ->setParam('useDefaultControllerAlways',true);
       	
     	$front->registerPlugin($appPlugin);
       	
       	//set_error_handler(array('SProject','errorHandler'));
	}
	
	public static function errorHandler($errno, $errstr, $errfile, $errline){
		switch ($errno) {
	    case E_USER_ERROR:
	        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
	        echo "  Fatal error on line $errline in file $errfile";
	        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
	        echo "Aborting...<br />\n";
	        exit(1);
	        break;
	
	    case E_USER_WARNING:
	        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
	        break;
	
	    case E_USER_NOTICE:
	        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
	        break;
	
	    default:
	        echo "Unknown error type: [$errno] $errstr<br />\n";
	        break;
	    }
	
	    /* Don't execute PHP internal error handler */
	    return true;
		
	}
	
	protected function registryGlobals(){
		
		if(!Zend_Registry::isRegistered('config')){
			try{
				self::$config = new Zend_Config_Ini(SP_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.ini',SP_APP_SECTION);
				Zend_Registry::set('config',self::$config);
			}catch(Zend_Config_Exception $e){
				die('Error to registry configrations: '.$e->getMessage());
			}
		}
		
		$config = Zend_Registry::get('config');
		if(!Zend_Registry::isRegistered('cache')){
			try{
				$frontendOptions = array('lifeTime'=>(int)$config->cache->lifeTime,'automatic_serialization'=>true);
				$backendOptions = array('cache_dir'=>$config->cache->dir);
				
				self::$cache = Zend_Cache::factory('Core','File',$frontendOptions,$backendOptions);
				Zend_Registry::set('cache',self::$cache);
			}catch(Zend_Cache_Exception $e){
				die('Error to set cahce for application: '.$e->getMessage());
			}
		}
		
		if(!Zend_Registry::isRegistered('db')){
			try{
				self::$db = Zend_Db::factory($config->db->params->adapter,$config->db->params->toArray());
				Zend_Registry::set('db',self::$db);
			}catch(Zend_Db_Exception $e){
				die('Error to registry db instance :'.$e->getMessage());
			}
		}
		
		$mo_format = SP_LOCALE_PATH . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'lang.%s.mo';
		Zend_Translate::setCache(Zend_Registry::get('cache'));
		$appNamespace = new Zend_Session_Namespace('APP');
		if(!Zend_Registry::isRegistered('lang')){
			$mo_file = sprintf($mo_format,$config->lang->params->default);
			$translate = new Zend_Translate('gettext',$mo_file,$config->lang->params->default);
			Zend_Registry::set('lang',$translate);
			$appNamespace->locale = $config->lang->params->default;
		}else{
			if($appNamespace->locale !=Zend_Registry::get('lang')->getAdapter()->getLocale() ){
				Zend_Registry::get('lang')->getAdapter()->addTranslation(sprintf($mo_format,$appNamespace->locale),$appNamespace->locale,null);
				Zend_Registry::get('lang')->getAdapter()->setLocale($appNamespace->locale);
			}
		}
	}
}

?>