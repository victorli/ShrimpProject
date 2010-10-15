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
class SP_Validate_CellPhone extends Zend_Validate_Abstract{
	
	const NOT_PHONE = "notPhone";
	const INVALID_PHONE = "invalidCellPhone";
	const STRING_EMPTY = "stringEmpty";
	
	protected $_messageTemplates = array(
		self::NOT_PHONE 	=> "'%value%' is not a phone number",
		self::INVALID_PHONE => "''%value% is not a cell phone number",
		self::STRING_EMPTY	=> "Please provide a phone number"
	);
	
	public function isValid($value){
		if(!is_string($value) && !is_int($value)){
			$this->_error(self::NOT_PHONE);
			return false;
		}
		
		$this->_setValue($value);
		$numbersOnly = preg_replace("[^0-9]","",$value);
		if(strlen($numbersOnly) != strlen($value) 
			|| !preg_match('/^(13|15|18)\d{9}$/',$numbersOnly)){
				
			$this->_error(self::INVALID_PHONE);
			return false;
		}
		
		return true;
	}
}

?>