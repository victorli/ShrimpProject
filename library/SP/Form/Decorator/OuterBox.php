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
class SP_Form_Decorator_OuterBox
	extends Zend_Form_Decorator_Abstract
{
	public function render($content){
		$options = $this->getOptions();
		$box = "<div ";
		if(isset($options['attrs'])){
			foreach($options['attrs'] as $attr => $value){
				$box .=$attr."='".$value."' ";
			}
		}
		
		$box .=">";
		$title = "";
		if(isset($options['title'])){
			$title .= "<h3>".$options['title']."</h3><hr>";
		}
		
		$error = "";
		$errors = $this->getErrMessages();
		if(count($errors)){
			$error .="<ul class='errors'>";
			foreach($errors as $key=>$value){
				$error .="<li>".$key.":";
				foreach($value as $e){
					$error .=$e.",";
				}
				$error = substr($error,0,-1);
				$error .="</li>";
			}
			
			$error .="</ul>";
		}
		if($this->getPlacement() === parent::APPEND)
			$output = $box . $title . $content . $error . "</div>";
		else
			$output = $box . $title . $error . $content . "</div>";
		
		return $output;
	}
	/**
	 * Get all error messages array
	 * @return Array $errors
	 */
	public function getErrMessages(){
		$errors = array();
		$element = $this->getElement();
		foreach($element->getElements() as $el){
			$err = $el->getMessages();
			if(count($err))
				$errors[$el->getLabel()] = $err;
		}
		
		return $errors;
	}
}

?>