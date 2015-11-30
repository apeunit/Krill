<div class="row">
	<?php echo $this->Form->postLink(__('Start all Streams'), array('controller' => 'raspberries', 'action' => 'startAllStreams'), array('class' => 'btn btn-default'), __('Sure?')); ?>
	<?php echo $this->Form->postLink(__('Stop all Streams'), array('controller' => 'raspberries', 'action' => 'stopAllStreams'), array('class' => 'btn btn-danger'), __('Sure?')); ?>
</div>
<div class="row raspberries index">
	<h2><?php echo __('Raspberries'); ?></h2>
	<table class="table">
	<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th><?php echo __('Ping') ?></th>
		<th><?php echo $this->Paginator->sort('name'); ?></th>
		<th><?php echo $this->Paginator->sort('serial'); ?></th>
		<th><?php echo $this->Paginator->sort('ip'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($raspberries as $raspberry): ?>
	<tr>
		<td class="raspiID"><?php echo h($raspberry['Raspberry']['id']); ?>&nbsp;</td>
		<td><?php echo h($raspberry['Raspberry']['created']); ?>&nbsp;</td>
		<td><?php echo h($raspberry['Raspberry']['modified']); ?>&nbsp;</td>
		<td><?php
			if (isset($raspberry['Log'][0]['created'])) {
				echo $this->Krikkit->showLastPing($raspberry['Log'][0]['created']);
			}
		?>&nbsp;</td>
		<td><?php echo h($raspberry['Raspberry']['name']); ?>&nbsp;</td>
		<td><?php echo h($raspberry['Raspberry']['serial']); ?>&nbsp;</td>
		<td><?php echo h($raspberry['Raspberry']['ip']); ?>&nbsp;</td>
		<td><span id="videostate<?php echo h($raspberry['Raspberry']['id']); ?>" class="label label-default">loading...</span></td>
		<td class="actions">
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					Actions
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><?php echo $this->Form->postLink(__('Start Stream'), array('action' => 'startStreamingVideo', $raspberry['Raspberry']['id']), null, __('Are you sure you want to start Stream # %s?', $raspberry['Raspberry']['id'])); ?></li>
					<li><?php echo $this->Form->postLink(__('Stop Stream'), array('action' => 'stopStreamingVideo', $raspberry['Raspberry']['id']), null, __('Are you sure you want to stop Stream # %s?', $raspberry['Raspberry']['id'])); ?></li>
					<li><?php echo $this->Form->postLink(__('Reboot'), array('action' => 'reboot', $raspberry['Raspberry']['id']), null, __('Are you sure you want to reboot # %s?', $raspberry['Raspberry']['id'])); ?></li>
					<li><?php echo $this->Form->postLink(__('Shutdown'), array('action' => 'shutdown', $raspberry['Raspberry']['id']), null, __('Are you sure you want to reboot # %s?', $raspberry['Raspberry']['id'])); ?></li>
					<li class="divider"></li>
					<li><?php echo $this->Html->link(__('Show Logs'), array('controller' => 'logs', 'action' => 'index', 'raspi' => $raspberry['Raspberry']['id'])); ?></li>
					<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $raspberry['Raspberry']['id'])); ?></li>
					<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $raspberry['Raspberry']['id'])); ?></li>
					<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $raspberry['Raspberry']['id']), null, __('Are you sure you want to delete # %s?', $raspberry['Raspberry']['id'])); ?></li>
				</ul>
			</div>
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

<?php echo $this->Html->script('krikkit', array('block' => 'script')); ?>