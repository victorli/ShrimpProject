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
class SP_Controller_Action_Helper_Solr extends Zend_Controller_Action_Helper_Abstract{
	/**
	 * 
	 * @var Apache_Solr_Service
	 */
	protected $_solrService = null;
	protected $_host = "localhost";
	protected $_port = 8080;
	protected $_path = "/solr";
	
	protected $documents = array();
	
	/**
	 * @return Apache_Solr_Service
	 */
	public function getSolrService(){
		$this->_setSolrService();
		
		return $this->_solrService;
	}
	/**
	 * Get or Create Solr Service
	 * 
	 * @return void
	 */
	protected function _setSolrService(){
		if(null === $this->_solrService){
			$this->_solrService = new Apache_Solr_Service($this->_host,$this->_port,$this->_path);
			if(!$this->_solrService->ping()){
				trigger_error("the Apache Solr Service is unavaliable");
				exit;
			}
		}
	}
	/**
	 * 
	 * @param Apache_Solr_Document or Array $parts
	 * 
	 * @return SP_Controller_Action_Helper_Solr
	 */
	public function pushDocuments($parts){
		$this->_setSolrService();
		if($parts instanceof Apache_Solr_Document){
			$this->documents[] = $parts;
		}else if(is_array($parts)){
			foreach($parts as $item => $fields){
				if($fields instanceof Apache_Solr_Document){
					$this->documents[] = $fields;
				}else{
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
					
					$this->documents[] = $part;
				}
			}
		}else{
			trigger_error("the paramter \$part must be an object of Apache_Solr_Document or an array");
		}
		
		return $this;
	}
	/**
	 * Commit all pushed documents to the Solr Server
	 */
	public function commit(){
		$this->_setSolrService();
		if(count($this->documents)){
			try{
				$this->_solrService->addDocuments($this->documents);
				$this->_solrService->commit();
				$this->_solrService->optimize();
				$this->_clear();
			}catch(Exception $e){
				trigger_error($e->getMessage(),$e->getCode());
			}
		}else{
			trigger_error("There is not document for committing");
		}
	}
	
	protected function _clear(){
		if(count($this->documents)){
			unset($this->documents);
			$this->documents = array();
		}
	}
	/**
	 * 
	 * @param string $query
	 * @param int $offset default 0
	 * @param int $limit  default 10
	 * @param array $params additional array for parameters
	 * @param string $method submit method default Apache_Solr_Service::METHOD_GET
	 * 
	 * @return Apache_Solr_Response
	 */
	public function search($query,$offset,$limit,$params,$method){
		$this->_setSolrService();
		return $this->_solrService->search($query,$offset,$limit,$params,$method);
	}
	/**
	 * 
	 * @param string $host
	 * @param int $port
	 * @param string $path
	 * @param array $params
	 * 
	 * @return SP_Controller_Action_Helper_Solr
	 */
	public function direct($host,$port,$path,$params = null){
		if(isset($host)) $this->_host = $host;
		if(isset($port)) $this->_port = $port;
		if(isset($path)) $this->_path = $path;
		$this->_setSolrService();
		
		return $this;
	}
}

?>