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
	
	public function indexAction(){
		$viewRenderer = $this->_helper->viewRenderer;
		$viewRenderer->setNoRender();
		
		$viewRenderer->render('body.left','body_left',false);
		$viewRenderer->render('body.right','body_right',false);
	}
	
	public function goAction(){
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		//$viewRenderer = $this->_helper->viewRenderer;
		echo "modouleDir:".$viewRenderer->getModuleDirectory();
		echo "<br>";
		echo "script file:".$viewRenderer->getViewScript();
		echo "<br>";
		echo "script spec:".$viewRenderer->getViewScriptPathSpec();
		echo "<br>";
		echo "script action:".$viewRenderer->getScriptAction();
		
		$this->view->title='hello world -- Go';
		$this->view->content='hahahahahhahahahhaha';
	}
}

?>