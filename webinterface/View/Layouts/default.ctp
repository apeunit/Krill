<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Raspistream');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		echo $this->Html->meta('icon');
// 		echo $this->Html->meta('icon', '/img/favicon.png');
// 		echo $this->Html->css('cake.generic');
		echo $this->fetch('meta');
		echo $this->Html->css('bootstrap');
		echo $this->fetch('css');
	?>
</head>
<body>
	<?php echo $this->element('navbar'); ?>
	<div class="container">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
	<?php echo $this->element('sql_dump'); ?>
	
</body>
	<?php 
		echo $this->Html->script('http://code.jquery.com/jquery.js');
		echo $this->Html->script('bootstrap.min');
		echo $this->fetch('script');
	?>
</html>
