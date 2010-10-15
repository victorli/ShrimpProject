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
class User_IndexController extends SP_Controller_Action{
	
	public function indexAction(){
		$this->_redirect('/user/index/list/');
	}
	
	public function loginAction(){
		if(Zend_Auth::getInstance()->hasIdentity())
			$this->_redirect('/');
		
		$form = new SP_Form_Login();
		$request = $this->getRequest();
		if($request->isPost() && $form->isValid($_POST)){
			$username = $request->getParam('name');
			$pwd = $request->getParam('pwd');
			
			$authAdapter = SP_Application::getAuthAdapter();
			$authAdapter->setIdentity($username)
				 		->setCredential($pwd);
			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($authAdapter);
			
			if($result->isValid()){
				$storage = $auth->getStorage();
				$storage->write($authAdapter->getResultRowObject(null,'pwd'));
				$this->_redirect('/');
			}else{
				$this->view->error = "Username".$username." or password:".$pwd." is not right.";
			}
		}
		
		$this->view->form = $form;
		$this->view->title = $this->translator->_('Login');
		
		$this->getResponse()->insert('content',$this->render('login',null,true));
	}
	
	public function editAction(){
		$form = new SP_Form_User_Edit();
		$id = $this->getRequest()->getParam('id');
		if(is_numeric($id)){
			$user = new SP_User_Model_User();
			$this->view->form = $form->populate($user->retrive($id)->toArray());
		}else{
			$this->view->form = $form;
		}
		
		$this->getResponse()->insert('content',$this->render(null,null,true));
	}
	
	public function logoutAction(){
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/user/login');
	}
	
	public function listAction(){
		$user = new SP_User_Model_User();				  
		$this->view->htmlTable(array(
											'table'=>array('class'=>'sortable','cellpadding'=>2,'cellspacing'=>1,'width'=>'100%'),
											'thead'=>array('class'=>'thead','title'=>'click to sort'),
											'tbody'=>array('class'=>'tbody'),
											'checkbox'=>true,
											'colOpts'=>true,
											))
					->setView($this->view)
					->setOperations(array('edit'=>'/user/index/edit/id/%d',))					
					->headTitle(array('Id','Name','Pwd','Role','Real Name'))
				  	->body($this->_helper->Paginator($user->fetchAll(),true,15,$this->_getParam('page',1)))
				  	->footBar(array('Delete'=>'delete','Export'=>'export'));
		
		$this->view->actionBar(array(
			'type'=>'button',
			'actions'=>array(
				array('label'=>'New User','link'=>'/user/edit'),
				array('label'=>'Filters','link'=>'/user/filters')
			)
		));
		
		
		$this->getResponse()->insert('content',$this->render(null,null,true));
	}
	
	public function saveAction(){
		$request = $this->getRequest();
		if($request->isPost()){
			$user = new SP_User_Model_User();
			$form = new SP_Form_User_Edit();
			if($form->isValid($_POST)){
				$user->setId($form->getValue('id'))
					 ->setName($form->getValue('name'))
					 ->setPwd($form->getValue('pwd'))
					 ->setRole($form->getValue('role'))
					 ->setTrueName($form->getValue('true_name'))
					 ->save();
				
				$this->_redirect('/user/list/1');
			}else{
				$this->view->form = $form->populate($_POST);
			}
		}
		
		$this->getResponse()->insert('content',$this->render('edit',null,true));
	}
}

?>