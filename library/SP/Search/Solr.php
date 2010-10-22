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
class SP_Search_Solr extends Apache_Solr_Service{

	public function __construct($host = 'loclahost',$port = '8080',$path = '/solr'){
		parent::__construct($host,$port,$path);
	}
	/**
	 * make of Apache_Solr_Documents through an array
	 * @param array $parts
	 * @return Apache_Solr_Document Array
	 */
	public function arrayToDocument(Array $parts){
		$documents = array();
		if(!is_array($parts) || count($parts) == 0){
			throw new Exception("parameter \$parts must be an array.");
		}
		
		foreach($parts as $item => $fields){
			$part = new Apache_Solr_Document();
			foreach($fields as $key => $value){
				if(is_array($value)){
					foreach($value as $datum){
						$part->setMultiValue($key,$datum);
					}
				}else{
					$part->setField($key,$value);
				}
			}
			$documents[] = $part;
		}
		
		return $documents;
	}
	
	
}

?>