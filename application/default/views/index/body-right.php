<td class="body_right">
<script>
$(function(){
	$("#tabs ul li a").bind('mouseover',function(){
		if(!$(this).hasClass("selectedTab")){
			$(this).addClass("overTab");
		}
	}).bind('mouseout',function(){
		if($(this).hasClass("overTab"))
			$(this).removeClass('overTab');
	}).bind('click',function(){
		if($(this).hasClass("selectedTab"))
			return;
		$("#tabs ul li a").removeClass("selectedTab");
		if($(this).hasClass("overTab"))
			$(this).removeClass("overTab");
		$(this).addClass("selectedTab");
	});
});
</script>
<div id="tabs">
	<ul>
		<li><a href="#a" class="selectedTab">项目</a></li>
		<li><a href="#b">Tab2</a></li>
		<li><a href="#c">Tab3</a></li>
		<li><a href="#d">Tab4</a></li>
	</ul>
	<div id="tab_content_containner">
		tab content containner
	</div>
</div>
<hr/>
<div id="body_main">

</div>
</td>