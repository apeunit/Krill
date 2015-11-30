<div class="row logs index">
	<h2><?php echo __('Logs'); ?></h2>
	<table class="table">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('message'); ?></th>
			<th><?php echo $this->Paginator->sort('raspberry_id'); ?></th>
	</tr>
	<?php foreach ($logs as $log): ?>
	<tr>
		<td><?php echo h($log['Log']['id']); ?>&nbsp;</td>
		<td><?php echo h($log['Log']['created']); ?>&nbsp;</td>
		<td><?php echo h($log['Log']['modified']); ?>&nbsp;</td>
		<td><?php echo h($log['Log']['type']); ?>&nbsp;</td>
		<td><?php echo h($log['Log']['message']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($log['Raspberry']['name'], array('controller' => 'raspberries', 'action' => 'view', $log['Raspberry']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
