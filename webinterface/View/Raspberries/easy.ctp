<div class="row">
	<div class="col-md-6">
		<?php echo $this->Form->postLink(__('Start Video'), array('controller' => 'raspberries', 'action' => 'startAllStreams'), array('class' => 'btn btn-default btn-lg btn-block'), __('Sure?')); ?>
	</div>
	<div class="col-md-6">
		<?php echo $this->Form->postLink(__('Stop Video'), array('controller' => 'raspberries', 'action' => 'stopAllStreams'), array('class' => 'btn btn-default btn-lg btn-block'), __('Sure?')); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php 
			if (isset($isStreamingAudio) && $isStreamingAudio) {
				echo $this->Form->postLink(__('Stop Audio'), array('controller' => 'raspberries', 'action' => 'stopLocalAudio'), array('class' => 'btn btn-success btn-lg btn-block'), __('Sure?'));
			} else {
				echo $this->Form->postLink(__('Start Audio'), array('controller' => 'raspberries', 'action' => 'startLocalAudio'), array('class' => 'btn btn-default btn-lg btn-block'), __('Sure?'));
			}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php 
			if (isset($isStreaming) && $isStreaming) {
				echo $this->Form->postLink(__('Stop Stream'), array('controller' => 'raspberries', 'action' => 'stopLocalStream'), array('class' => 'btn btn-success btn-lg btn-block'), __('Sure?'));
			} else {
				echo $this->Form->postLink(__('Start Stream'), array('controller' => 'raspberries', 'action' => 'startLocalStream'), array('class' => 'btn btn-default btn-lg btn-block'), __('Sure?'));
			}
		?>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<form id="form1" method="post" target="target" action="<?php echo Configure::read('Record.server'); ?>">
			<input type="hidden" name="server" value="<?php echo Configure::read('Record.server'); ?>" />
			<input type="hidden" name="app" value="<?php echo Configure::read('Record.application'); ?>" />
			<input type="hidden" name="streamname" value="<?php echo Configure::read('Record.streamname'); ?>" />
			<input type="hidden" name="format" value="<?php echo Configure::read('Record.format'); ?>" />
			<input type="hidden" name="output" value="<?php echo Configure::read('Record.custom_output_path'); ?>" />
			<?php if (Configure::read('Record.append')): ?>
				<input type="hidden" name="append" value="true" />
			<?php endif; ?>
			<?php if (Configure::read('Record.record_data')): ?>
				<input type="hidden" name="recorddata" value="true" />
			<?php endif; ?>
			<?php if (Configure::read('Record.start_on_keyframe')): ?>
				<input type="hidden" name="startonkeyframe" value="true" />
			<?php endif; ?>
			<?php if (Configure::read('Record.overwrite')): ?>
				<input type="hidden" name="version" value="false" />
			<?php else: ?>
				<input type="hidden" name="version" value="true" />
			<?php endif; ?>
			<input type="hidden" name="action" value="startRecording" />
			<input class="btn btn-lg btn-block btn-success" type="submit" value="Start Recording" />
		</form>
		
		<form id="form1" method="post" target="target" action="<?php echo Configure::read('Record.server'); ?>">
			<input type="hidden" name="server" value="<?php echo Configure::read('Record.server'); ?>" />
			<input type="hidden" name="app" value="<?php echo Configure::read('Record.application'); ?>" />
			<input type="hidden" name="streamname" value="<?php echo Configure::read('Record.streamname'); ?>" />
			<input type="hidden" name="format" value="<?php echo Configure::read('Record.format'); ?>" />
			<input type="hidden" name="output" value="<?php echo Configure::read('Record.custom_output_path'); ?>" />
			<?php if (Configure::read('Record.append')): ?>
				<input type="hidden" name="append" value="true" />
			<?php endif; ?>
			<?php if (Configure::read('Record.record_data')): ?>
				<input type="hidden" name="recorddata" value="true" />
			<?php endif; ?>
			<?php if (Configure::read('Record.start_on_keyframe')): ?>
				<input type="hidden" name="startonkeyframe" value="true" />
			<?php endif; ?>
			<?php if (Configure::read('Record.overwrite')): ?>
				<input type="hidden" name="version" value="false" />
			<?php else: ?>
				<input type="hidden" name="version" value="true" />
			<?php endif; ?>
			<input type="hidden" name="action" value="stopRecording" />
			<input class="btn btn-lg btn-block btn-danger" type="submit" value="Stop Recording" />
		</form>
	</div>
	
	<div class="col-md-9">
		<iFrame name="target" id="target" width="100%" height="100"></iFrame>
	</div>
</div>