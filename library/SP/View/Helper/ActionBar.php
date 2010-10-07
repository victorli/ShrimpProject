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
class SP_View_Helper_ActionBar extends Zend_View_Helper_Abstract{
	
	protected $type = 'a';
	protected $action_str = "";
	
	public function __construct(){}
	
	public function actionBar($options = null){
		if(is_null($options))
			return $this;
			
		if(!is_array($options))
			throw new Exception('Array needed for the argument');
		if(key_exists('type',$options))
			$this->type = $options['type'];

		if(!key_exists('actions',$options))
			throw new Exception('Key "actions" needed');
		foreach($options['actions'] as $action){
			if($this->type == 'a'){
				$this->action_str .="<a href='".$action['link']."'>".$action['label']."</a>&nbsp;&nbsp;";
			}else if($this->type == 'button'){
				$this->action_str .="<button onclick=\"javascript:window.location='".$action['link']."'\">".$action['label']."</button>&nbsp;&nbsp;";
			}
		}
		
		return $this;
	}
	
	public function __toString(){
		return $this->action_str;
	}
}

?>