/*
 * Copyright (c) luckylzs@gmail.com
 * Version v0.1
 * 
 * <div id='tabs'>
 * 		<ul>
 * 			<li><a href="#content">Tab1</a></li>
 * 			<li><a href="#content">Tab2</a></li>
 * 			<li><a href="#content">Tab3</a></li>
 * 		</ul>
 * 		<div id="content">
 * 			<p></p>
 * 		</div>
 * </div>
 */

jQuery.simpleTabs = function(options){
	var idTag = "#"+options.id;
	if($(idTag).length ==0 ) return;
	
	var $jUl = $(idTag+" ul");
	if($jUl.length == 0) return;
	
	$jUl.css({"line-style-type":"none","padding-left":"5px","margin":"0"});
	
	var $jLi = $(idTag+" ul li");
	var liCss = {"float":"left","padding-left":"5px","border":"1xp solid black","margin-left":"5px"};
	$jLi.css(liCss);
	
	var $jLi_a = $(idTag+" ul li a");
	$jLi_a.css({"text-decoration":"none"});
	
	var tabSeletedCssForLi = {"background-color":"black"};
	var tabSelectedCssForA = {"color":"white"};
	
	$jLi.bind("mouseover",function(){
		$jLi.css(tabSelectedCssForLi);
		$jLi_a.css(tabSelectedCssForA);
	});
	
	$jLi_a.bind('click',function()){
		
	}
};


