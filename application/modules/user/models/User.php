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
class SP_User_Model_User{

	protected $_table_name = 'users';
	protected $id = null;
	protected $name = null;
	protected $pwd = null;
	protected $pwd_salt = null;
	protected $role = 'member';
	protected $true_name = null;
	
	private $data = array();
	/**
	 * 
	 * @var SP_User_Model_DbTable_User
	 */
	protected $table = null;
	
	public function __construct(array $params = null){
		$this->table = new SP_User_Model_DbTable_User();
		if(is_array($params)){
			foreach($params as $field=>$value){
				$this->$field = $value;
			}
		}
		
		$this->clear();
	}
	/**
	 * 
	 * @param integer $id
	 * @return SP_User_Model_User
	 */
	public function setId($id){
		if(is_null($id) || empty($id))
			return $this;
		$this->id = $id;
		$this->data['id'] = $this->id;
		return $this;
	}
	/**
	 * 
	 * @param string $name
	 * @return SP_User_Model_User
	 */
	public function setName($name){
		$this->name = $name;
		$this->data['name'] = $this->name;
		return $this;
	}
	/**
	 * 
	 * @param string $pwd
	 * @return SP_User_Model_User
	 */
	public function setPwd($pwd){
		$this->pwd = $pwd;
		$this->data['pwd'] = $this->pwd;
		return $this;
	}
	/**
	 * 
	 * @param string $role
	 * @return SP_User_Model_User
	 */
	public function setRole($role){
		$this->role = $role;
		$this->data['role'] = $this->role;
		return $this;
	}
	/**
	 * 
	 * @param string $name
	 * @return SP_User_Model_User
	 */
	public function setTrueName($name){
		$this->true_name = $name;
		$this->data['true_name'] = $this->true_name;
		return $this;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getPwd(){
		return $this->pwd;
	}
	
	public function getRole(){
		return $this->role;
	}
	
	public function getTrueName(){
		return $this->true_name;
	}
	
	public function login($username,$pwd){
		if(is_string($username))
			$this->name = $username;
		if(is_string($pwd))
			$this->pwd = $pwd;
			
		if(is_null($this->name) || empty($this->name))
			return false;
		$select = $this->table->select();
		$select->from($this->_table_name,"*");
		$select->where('name = ?',$username);
		$select->where('pwd = ?',$pwd);
		
		$row = $this->table->fetchRow($select);
		if(is_null($row))
			return false;
			
		$this->_walk($row);
		
		return true;
	}
	
	public function save(){
		if(is_null($this->id) || empty($this->id)){
			$this->table->insert($this->data);
		}else{
			$this->table->update($this->data,$this->table->getAdapter()->quoteInto('id=?',$this->id));
		}
	}
	/**
	 * 
	 * @param $id primary key
	 * @return SP_User_Model_User
	 */
	public function retrive($id){
		$row = $this->table->find($id);
		$this->_walk($row);
		return $this;
	}
	/**
	 * @return rows array
	 */
	public function fetchAll(){
		return $this->table->fetchAll(
				$this->table->select()
						    ->from($this->_table_name,array('id','name','pwd','role','true_name'))
				)->toArray();
	}
	/**
	 * 
	 * @param Zend_DB_Table_Row $rows
	 * 
	 * @return void
	 */
	protected function _walk($row){
		if($row instanceof Zend_Db_Table_Row){
			foreach($row as $field=>$value){
				$this->$field = $value;
			}
		}else{
			trigger_error('Error $row type');
		}
	}
	
	public function clear(){
		if(count($this->data)>0){
			unset($this->data);
			$this->data = array();
		}
	}
	
	public function __toString(){
		return "User Values: ".
				"id=>".$this->id.
			   	"name=>".$this->name.
				"pwd=>".$this->pwd.
				"role=>".$this->role.
				"true name=>".$this->true_name;
	}
}

?>