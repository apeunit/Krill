<div class="logs view">
<h2><?php echo __('Log'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($log['Log']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($log['Log']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($log['Log']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($log['Log']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Message'); ?></dt>
		<dd>
			<?php echo h($log['Log']['message']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Raspberry'); ?></dt>
		<dd>
			<?php echo $this->Html->link($log['Raspberry']['name'], array('controller' => 'raspberries', 'action' => 'view', $log['Raspberry']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Log'), array('action' => 'edit', $log['Log']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Log'), array('action' => 'delete', $log['Log']['id']), null, __('Are you sure you want to delete # %s?', $log['Log']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Logs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Log'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Raspberries'), array('controller' => 'raspberries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Raspberry'), array('controller' => 'raspberries', 'action' => 'add')); ?> </li>
	</ul>
</div>
