<div class="row raspberries form">
<?php echo $this->Form->create('Raspberry', array('role' => 'form')); ?>
	<fieldset>
		<legend><?php echo __('Add Raspberry'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('serial');
		echo $this->Form->input('ip');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>