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
class IndexController extends Zend_Controller_Action{
	
	var $table = null;
	public function init(){
		project_models_table::setDefaultAdapter(Zend_Registry::get('db'));
		$this->table = new project_models_table();
		parent::init();
	}
	/**
	 * get all projects
	 */
	public function indexAction(){
		$projects = $this->table->fetchAll(null,'id desc',10,0);
		$viewRenderer = $this->_helper->viewRenderer;
		
		if(count($projects))
			$this->view->projects = $projects;
		else
			$this->view->edit_form = $this->getForm();
	}
	
	public function topNavAction(){
		
	}
	
	public function saveAction(){
		
	}
	
	protected function getForm(){
		$form = new Zend_Form();
		
		$form->setAction('/project/save')
			 ->setMethod('post')
			 ->setAttrib('id','edit_form')
			 ->setAttrib('class','edit_form');
			 
		$form->addElement('text','title',array('label'=>'Title','required'=>true));
		$form->addElement('text','start_date',array('label'=>'Start date','required'=>true,'value'=>Date('Y-m-d')));
		$form->addElement('text','end_date',array('label'=>'End date','required'=>false));
		/*$priority = new Zend_Form_Element_Radio();
		$priority->setLabel('Priority')
				 ->setName('priority');
		$form->addElement($priority);*/
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOptions(array('Offered'=>'Offered','Ordered'=>'Ordered','Working'=>'Working','Ended'=>'Ended','Stopped'=>'Stopped','Re-Opened'=>'Re-Opened','Waiting'=>'Waiting'))
			   ->setValue('Offered');
		$form->addElement($status);
		$form->addElement('text','budget',array('label'=>'Budget'));
		
		return $form;
	}
}

?>