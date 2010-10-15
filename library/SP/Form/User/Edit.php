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
class SP_Form_User_Edit extends SP_Form{
	
	public function init(){
		
		$this->setName('Form User Edit');
		$this->setAttrib('class','Form_Edit');
		
		$this->setMethod('post')
			 ->setAction('/user/save');
			 
		$id = new Zend_Form_Element_Hidden('id');

		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name:')
			 ->setRequired(true)
			 ->addValidator('alnum')
			 ->addValidator('regex',false,array('/^[a-z]+/'))
			 ->addValidator('stringLength',false,array(4,12))
			 ->addFilter('StringToLower');
		
		$pwd = new Zend_Form_Element_Password('pwd');
		$pwd->setLabel('Password:')
			->setRequired(true);
		$re_pwd = new Zend_Form_Element_Password('re_pwd');
		$re_pwd->setLabel('Re-password:')
			   ->setRequired(true);
		
		$role = new Zend_Form_Element_Select('role');
		$role->setLabel('Role:')
			 ->addMultiOptions(array(
			array('key'=>'guest','value'=>'Guest'),
			array('key'=>'memeber','value'=>'Member'),
			array('key'=>'admin','value'=>'Admin')))
			->setDescription('Choose one role');
			
		
		$truename = new Zend_Form_Element_Text('true_name');
		$truename->setLabel('Real Name:');
		
		$phone = new Zend_Form_Element_Text('cellphone');
		$phone->setLabel('Cell Phone:')
			  ->addValidator(new SP_Validate_CellPhone());
		
		$btnSubmit = new Zend_Form_Element_Submit('submit');
		$btnSubmit->setLabel('Submit');
		
		$this->addElements(array($id,$name,$pwd,$re_pwd,$role,$truename,$phone,$btnSubmit));
		
		parent::init();	
		
		$this->addDecorator('OuterBox',array('attrs'=>array('class'=>'form user_edit_form'),'title'=>'Edit/Create User','placement'=>'PREPEND'));
		$id->removeDecorator('Label');
		$btnSubmit->removeDecorator('Label');
	}
	
	public function isValid($data){
		if(parent::isValid($data)){
			if($data['pwd'] !== $data['re_pwd']){
				$this->getElement('pwd')->addError('Password should be the same ');
				return false;
			}
			return true;
		}
		return false;
	}
}

?>