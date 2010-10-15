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

class Project_IndexController extends SP_Controller_Action{
	
	public function indexAction(){
		$this->_redirect('project/list/1');
	}
	
	public function listAction(){
		$this->view->actionBar(array(
			'type'=>'button',
			'actions'=>array(
				array('label'=>'Create Project','link'=>'project/edit'),
				array('label'=>'Export','link'=>'project/export')
			)
		));
		
		$project = new SP_Project_Model_Project();
		if(!is_null($project->fetchAll())){
			$this->view->htmlTable(array(
							'table'=>array('class'=>'sortable','cellpadding'=>'2','cellspacing'=>1,'width'=>'100%'),
							'thead'=>array('title'=>'click to sort'),
							'checkbox'=>true
							))
					   ->setView($this->view)
					   ->headTitle(array())
					   ->body($this->_helper->Paginator($project->fetchAll(),true,15,$this->_getParam('page',1)))
					   ->footBar(array('Delete'=>'delete','Export'=>'export'));
		}
		
		$this->getResponse()->insert('content',$this->render(null,null,true));
		
	}
	
	public function editAction(){
		
	}
	
	public function saveAction(){
		
	}
	
	public function deleteAction(){
		
	}
	
	public function exportAction(){
		
	}
}

?>