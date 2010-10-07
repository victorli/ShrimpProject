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

class SP_Controller_Plugin_AppPlugin extends Zend_Controller_Plugin_Abstract{
	
	public function preDispatch($request){
		$view = SP_Application::getView();
		$translator = Zend_Registry::get('Zend_Translate');
		$params = array();
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()){
			$view->hasLogin = true;
			$view->welcome = $translator->_('Welcome');
			$view->user = $auth->getIdentity()->name;
			$view->logout = $translator->_('logout');
			$view->account = $translator->_('Account');
			$view->user_id = $auth->getStorage()->read()->id;
			$view->user_role = $auth->getStorage()->read()->role;
			$view->user_name = $auth->getStorage()->read()->true_name;
			$view->admin = $translator->_('Admin');
			if(is_null($view->user_name))
				$view->user_name = $view->user;
		}else{
			$view->hasLogin = false;
			$view->login = $translator->_('Login');
			if($this->_request->getModuleName()!= 'user' && $this->_request->getActionName()!='login'){
				Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')->gotoUrl('/user/login');
			}
		}
		
		$view->menu = $this->getTopMenu();
		$view->addScriptPath(SP_APP_PATH . DIRECTORY_SEPARATOR . 'layouts');
		$this->_response->insert('header',$view->render("header.phtml"));
	}
	
	protected function getTopMenu(){
		$menu = SP_Application::getCache()->load('topMenu');
		if(!$menu){
			$translate = Zend_Registry::get('Zend_Translate');
			$menu = array(
				'home'=>array('label'=>$translate->_('Home'),'link'=>'/'),
				'project'=>array('label'=>$translate->_('Project'),'link'=>'/project'),
				'gantt'=>array('label'=>$translate->_('Gantt'),'link'=>'/gantt'),
				'statistic'=>array('label'=>$translate->_('Statistic'),'link'=>'/statistic'),
				'todo'=>array('label'=>$translate->_('Todo'),'link'=>'/todo'),
				'note'=>array('label'=>$translate->_('Note'),'link'=>'/note'),
				'calendar'=>array('label'=>$translate->_('Calendar'),'link'=>'/calendar'),
				'user'=>array('label'=>$translate->_('User'),'link'=>'/user'),
			);
			
			SP_Application::getCache()->save($menu,'topMenu');
		}
		return $menu;
	}
}

?>