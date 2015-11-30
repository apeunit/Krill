<!-- Static navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<?php echo $this->Html->link(__('Raspistream'), '/', array('class' => 'navbar-brand')); ?>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Raspberries <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><?php echo $this->Html->link(__('Easy'), array('controller' => 'raspberries', 'action' => 'easy')); ?></li>
						<li><?php echo $this->Html->link(__('List'), array('controller' => 'raspberries', 'action' => 'index')); ?></li>
						<li><?php echo $this->Html->link(__('Add'), array('controller' => 'raspberries', 'action' => 'add')); ?></li>
						<li><?php echo $this->Html->link(__('Livestream'), array('controller' => 'raspberries', 'action' => 'livestream')); ?></li>
					</ul>
				</li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Logs <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><?php echo $this->Html->link(__('List'), array('controller' => 'logs', 'action' => 'index')); ?></li>
						<li><?php echo $this->Html->link(__('Add'), array('controller' => 'logs', 'action' => 'add')); ?></li>
						<li><?php echo $this->Form->postLink(__('Delete Old'), array('controller' => 'logs', 'action' => 'deleteOld'), null, __('Are you sure you want to delete all old Logs?')); ?></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if (AuthComponent::user()): ?>
					<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'));?></li>
				<?php else: ?>
					<li><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'));?></li>
				<?php endif; ?>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</div>
