<div class="row">
	<div class="span12">
		<video id="example_video_1" class="video-js vjs-default-skin"
				controls preload="auto" width="1280" height="720"
				poster="/img/offline-05.png"
				data-setup='{"example_option":true}'>
			<?php if (!empty(Configure::read('streamurl.rtmp'))): ?>
				<source src="<?php echo Configure::read('streamurl.rtmp'); ?>" type="rtmp/mp4">
			<?php endif; ?>
			<?php if ($isApple && !empty(Configure::read('streamurl.hls'))): ?>
				<source src="<?php echo Configure::read('streamurl.hls'); ?>" type="application/x-mpegURL">
			<?php endif; ?>
			<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
		</video>
	</div>
</div>

<?php 
	echo $this->Html->css('video-js.min', array('block' => 'css'));
	echo $this->Html->script('video', array('block' => 'script'));
	echo $this->Html->script('videojs-media-sources', array('block' => 'script'));
	echo $this->Html->script('videojs.hls', array('block' => 'script'));
	echo $this->Html->scriptBlock('videojs.options.flash.swf = "/swf/video-js.swf"', array('inline' => false));
?>