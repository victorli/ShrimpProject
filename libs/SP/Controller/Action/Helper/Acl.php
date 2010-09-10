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

class SP_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract{
	
	protected $_action;
	protected $_auth;
	protected $_acl;
	protected $_controllerName;
	
	public function __construct(Zend_View_Interface $view = null, array $options = array()){
		$this->_auth = Zend_Auth::getInstance();
		$this->_acl = $options['acl'];
	}
	
	/**
	 * Hook into action controller initialization
	 * @return void
	 */
	public function init(){
		$this->_action = $this->getActionController();
		
		//add resource for this controller
		$this->_controllerName = $this->_action->getRequest()->getControllerName();
		if(!$this->_acl->has($this->_controllerName))
			$this->_acl->add(new Zend_Acl_Resource($this->_controllerName));
	}
	
	public function allow($roles = null, $actions = null){
		$this->_acl->allow($roles,$this->_controllerName,$actions);
		return $this;
	}
	
	public function deny($roles = null, $actions = null){
		$this->_acl->deny($roles,$this->_controllerName,$actions);
		return $this;
	}
}

?>