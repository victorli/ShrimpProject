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
class SP_View_Helper_HtmlTable extends Zend_View_Helper_Abstract{
	
	protected $table = "";
	protected $thead = "";
	protected $tbody = "";
	protected $checkbox = false;
	protected $headTitle = "<TR>";
	protected $headTitleArray = array();
	protected $body = "";
	protected $footbar = "";
	protected $even = null;
	protected $odd = null;
	
	public function __construct(){
		$this->even = "tr_even";
		$this->odd = "tr_odd";
	}
	
	public function htmlTable($options = null){
		if(is_array($options)){
			foreach($options as $key=>$attrs){
				if(is_array($attrs)){
					foreach($attrs as $attr=>$value)
						$this->$key .= $attr."='".$value."' "; 
				}
				else
					$this->$key = $attrs; 
			}
		}
		
		return $this;
	}
	
	public function headTitle($data){
		if(!is_array($data)){
			throw new Exception('$data should be an array');
		}	
		
		if($this->checkbox){
			$this->headTitle .="<TH width='20'>&nbsp;</TH>";
		}
		
		$this->headTitleArray = $data;
		foreach($data as $key=>$row){
			if(is_array($row)){
				$this->headTitle .= "<TH ".implode(" ",$row).">".$key."</TH>";
			}else{
				$this->headTitle .= "<TH>".$row."</TH>";
			}
		}
		
		$this->headTitle .="</TR>";
		
		return $this;
	}
	
	public function body($data){
		if(!is_array($data))
			throw new Exception('$data should be an array');
		
		foreach($data as $key=>$row){
			$this->body .= "<TR ";
			if($key%2 == 0)
				$this->body .= "class='".$this->even."'>";
			else
				$this->body .= "class='".$this->odd."'>";
				
			if($this->checkbox)
				$this->body .= "<TD><input class='row_checkbox' type='checkbox' name='selected' id='$key'></TD>";
				
			foreach($row as $col=>$value){
				$this->body .="<TD>".$value."</TD>";
			}
			
			$this->body .="</TR>";
		}
		
		return $this;
			
	}
	
	public function footBar($data){
		if(!$this->checkbox)
			return $this;
		$this->footbar .="<TR class='footBar'><TD colspan='".count($this->headTitleArray)."'>".
						"<img src='../theme/default/images/arrowLeft.png' />".
						"<a href='#' onclick='$(\"[class=row_checkbox]:checkbox\").attr(\"checked\",\"checked\")'>Check All</a>".
						"&nbsp;/&nbsp;".
						"<a href='#' onclick='$(\"[class=row_checkbox]:checkbox\").removeAttr(\"checked\")'>Uncheck All</a>";
		if(is_array($data)){
			$this->footbar .= "&nbsp;&nbsp;<select>".
							  "<option>".Zend_Registry::get('Zend_Translate')->_('With Selected')."</option>";
			foreach($data as $label=>$action){
				$this->footbar .="<option id='$action'>$label</option>";
			}
			
			$this->footbar .= "</select>";
		}
		$this->footbar .= "</TD></TR>";
		
		return $this;
	}
	
	public function __toString(){
		$html = "<table ".$this->table.">".
				"<thead ".$this->thead.">".
				$this->headTitle.
				"</thead>".
				"<tbody ".$this->tbody.">".
				$this->body.
				$this->footbar.
				"</tbody>".
				"</table>";
				
		return $html;
	}
}

?>