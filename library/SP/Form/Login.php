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
class SP_Form_Login extends Zend_Form{
	
	public function __construct($options = null){
		parent::__construct($options);
	}
	
	public function init(){
		
		$this->addPrefixPath('SP_Form_Decorator','SP/Form/Decorator/','decorator');
		
		$this->_view = SP_Application::getView();
		$this->setName('Form_Login');
		$this->setAttrib('class','Form_Edit');
		
		$this->setMethod('post')
			 ->setAction('/user/login');
		
		$username = new Zend_Form_Element_Text('name');
		$username->setLabel($this->getTranslator()->translate('UserName:'))
				 ->setRequired(true)
				 ->addValidator('NotEmpty',true)->setView($this->getView());
				 
		$password = new Zend_Form_Element_Password('pwd');
		$password->setLabel($this->getTranslator()->translate('Password:'))
				 ->setRequired(true)
				 ->addValidator('NotEmpty')->setView($this->getView());
				 
		$btnLogin = new Zend_Form_Element_Submit('submit');
		$btnLogin->setLabel($this->getTranslator()->_('Login'))->setView($this->getView());
		
		$this->addElements(array($username,$password,$btnLogin));
		
		$this->clearDecorators();
		$this->setDecorators(array(
			'FormElements',
			'Form',
			array('OuterBox',array('attrs'=>array('class'=>'form login_form'),'title'=>'Login','placement'=>'PREPEND'))
			)
		);
		
		$this->setElementDecorators(array(
			'ViewHelper',
			array('data'=>'HtmlTag',array('tag'=>'div','class'=>'form_row')),
			array('Label',array('tag'=>'div','class'=>'form_label'))
		));
		
		$btnLogin->removeDecorator('Label');
	}
}


?>