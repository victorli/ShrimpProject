<?php if(count($this->projects)):?>
<table class="table_list">
	<?php foreach($this->projects as $key=>$val):?>
	<tr>
		<td><?php echo $this->escape($val['title'])?></td>
		<td><?php echo $this->escape($val['start_date'])?></td>
		<td><?php echo $this->escape($val['end_date'])?></td>
		<td><?php echo $this->escape($val['priority'])?></td>
		<td><?php echo $this->escape($val['status'])?></td>
		<td><?php echo $this->escape($val['complete_percent'])?></td>
	</tr>
	<?php endforeach;?>
</table>
<?php else: ?>
<?php echo $this->edit_form ?>
<?php endif;?>