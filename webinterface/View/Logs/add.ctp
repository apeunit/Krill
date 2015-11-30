<div class="row logs form">
<?php echo $this->Form->create('Log'); ?>
	<fieldset>
		<legend><?php echo __('Add Log'); ?></legend>
	<?php
		echo $this->Form->input('type', array('type' => 'select', 'options' => $logTypes));
		echo $this->Form->input('message');
		echo $this->Form->input('raspberry_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
