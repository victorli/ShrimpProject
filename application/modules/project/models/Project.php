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
class SP_Project_Model_Project extends SP_Model_Abstract{
	protected $_table_name = 'projects';
	
	//fields defination
	protected $id = null;
	protected $name = null;
	protected $start_date = null;
	protected $end_date = null;
	protected $priority = 5;
	protected $status = null;
	protected $complete_percent = 0;
	protected $budget = 0.00;
	protected $contact = null;
	protected $tag = null;
	protected $parent = 0;
	protected $note_id = null;
	
	public function __construct($table_name = null){
		if(!is_null($table_name))
			$this->_table_name = $table_name;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getTableName(){
		return $this->_table_name;
	}
	
	public function setTableName($name){
		$this->_table_name = $name;
	}
	
	public function getStartDate(){
		return $this->start_date;
	}
	
	public function setStartDate($date){
		$this->start_date = $date;
	}
	
	public function getEndDate(){
		return $this->end_date;
	}
	
	public function setEndData($date){
		$this->end_date = $date;
	}
	
	public function getPriority(){
		return $this->priority;
	}
	
	public function setPriority($value){
		$this->priority = $value;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function setStatus($value){
		$this->status = $value;
	}
	
	public function getCompletePercent(){
		return $this->complete_percent;
	}
	
	public function setCompletePercent($value){
		$this->complete_percent = $value;
	}
	
	public function getBudget(){
		return $this->budget;
	}
	
	public function setBudget($value){
		$this->budget = $value;
	}
	
	public function getParent(){
		return $this->parent;
	}
	
	public function setParent($value){
		$this->parent = $value;
	}
	
	public function getNote(){
		return $this->note_id;
	}
	
	public function setNote($id){
		$this->note_id = $id;
	}
	
	public function save(){
		
	}
	
	public function retrive($id){
		
	}
	
	public function fetchAll(){
		
	}
	
	
}

?>