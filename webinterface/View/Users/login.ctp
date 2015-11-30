<div class="row">
	<div class="span12">
		<form action="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" role="form" class="form-inline" id="UserLoginForm" method="post" accept-charset="utf-8">
		<div class="form-group">
			<div class="input password">
				<label class="sr-only" for="UserPassword">Password</label>
				<input name="data[User][password]" class="form-control" type="password" id="UserPassword" placeholder="Password" />
			</div>
		</div>
		<button type="submit" class="btn btn-default">Log in</button>
		</form>
	</div>
</div>