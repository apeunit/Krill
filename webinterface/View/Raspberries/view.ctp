<?php //debug($raspberry); ?>
<div class="row">
	<div class="span12">
	<h2><?php echo __('Raspberry'); ?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($raspberry['Raspberry']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($raspberry['Raspberry']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($raspberry['Raspberry']['modified']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($raspberry['Raspberry']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Serial'); ?></dt>
			<dd>
				<?php echo h($raspberry['Raspberry']['serial']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
</div>
<div class="row">
	<div class="span12">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Edit Raspberry'), array('action' => 'edit', $raspberry['Raspberry']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete Raspberry'), array('action' => 'delete', $raspberry['Raspberry']['id']), null, __('Are you sure you want to delete # %s?', $raspberry['Raspberry']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List Raspberries'), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('Show Logs'), array('controller' => 'logs', 'action' => 'index', 'raspi' => $raspberry['Raspberry']['id'])); ?></li>
		</ul>
	</div>
</div>
<?php if (isset($raspberry['Log']) && !empty($raspberry['Log'])): ?>
<div class="row">
	<div class="span12">
		<h3><?php echo __('Related Logs'); ?></h3>
		<?php if (!empty($raspberry['Log'])): ?>
		<table class="table">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Modified'); ?></th>
			<th><?php echo __('Type'); ?></th>
			<th><?php echo __('Message'); ?></th>
			<th><?php echo __('Raspberry Id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($raspberry['Log'] as $log): ?>
			<tr>
				<td><?php echo $log['id']; ?></td>
				<td><?php echo $log['created']; ?></td>
				<td><?php echo $log['modified']; ?></td>
				<td><?php echo $log['type']; ?></td>
				<td><?php echo $log['message']; ?></td>
				<td><?php echo $log['raspberry_id']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'logs', 'action' => 'view', $log['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'logs', 'action' => 'edit', $log['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'logs', 'action' => 'delete', $log['id']), null, __('Are you sure you want to delete # %s?', $log['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
</div>
<?php endif; ?>
<div class="row">
	<div class="span12">
		<h3><?php echo __('Related Configs'); ?></h3>
		<?php if (isset($raspberry['Config']) && !empty($raspberry['Config'])): ?>
			<table class="table table-condensed table-bordered table-hover">
				<thead>
					<tr>
						<th><?php echo __('Key'); ?></th>
						<th><?php echo __('Value'); ?></th>
						<th><?php echo __('Default'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($raspberry['Config'] as $config): ?>
						<tr>
							<td><?php echo h($config['key']); ?></td>
							<td><?php echo h($config['value']); ?></td>
							<td><?php echo isset($config['default']) ? h($config['default']) : ''; ?></td>
							<td>
								<?php if (isset($config['id']) && !empty($config['value'])) {
									echo $this->Form->postLink(__('Delete'), array('controller' => 'configs', 'action' => 'delete', $config['id']), array('class' => 'btn btn-danger btn-xs'), __('Are you sure you want to delete %s?', $config['key']));
								}?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<div>
			<?php echo $this->Form->create('Config', array('action' => 'setConfig')); ?>
			<?php echo $this->Form->input('raspberry_id', array('type' => 'hidden', 'value' => $raspberry['Raspberry']['id'])); ?>
			<?php echo $this->Form->input('key'); ?>
			<?php echo $this->Form->input('value'); ?>
			<?php echo $this->Form->submit(__('Add Config')); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
