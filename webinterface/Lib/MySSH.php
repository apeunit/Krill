<?php 
// include('Net/SSH2.php');
// include('Crypt/RSA.php');
include(APP . 'Vendor/phpseclib/Net/SSH2.php');
include(APP . 'Vendor/phpseclib/Crypt/RSA.php');
// define('NET_SSH2_LOGGING', NET_SSH2_LOG_COMPLEX);

class MySSH {
	private $host;
	private $user;
	private $pass;
	private $port;
	private $ssh;
	
	public function __construct($host, $user, $pass, $port, $certFile) {
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->port = $port;
		$this->certFile = $certFile;
		
		if (!$this->connect()) {
			throw new Exception("Unable to connect to {$this->host}");
		}
	}
	
	public function connect() {
		$this->ssh = new Net_SSH2($this->host, $this->port);
		$key = new Crypt_RSA();
		$key->loadKey(file_get_contents($this->certFile));
		if (!$this->ssh->login($this->user, $key)) {
// 			debug($this->ssh->getErrors());
// 			debug($this->ssh->getLog());
			return false;
		} else {
			return true;
		}
	}
	
	public function exec($command) {
		$result = $this->ssh->exec($command);
		return $result;
	}
	
	public function isStreaming() {
		$ps = $this->exec('ps -A | grep gst-launch-');
		if (strpos($ps,'gst-launch-') !== false) {
			return true;
		}
		return false;
	}
	
// 	public function startStreamingAudio() {
// 		if (!$this->isStreaming()) {
// 			$command = 'gst-launch-1.0 -v rtpbin name=rtpbin alsasrc device=plughw:Device ! audioconvert ! audio/x-raw,channels=1,depth=16,width=16,rate=44100 ! rtpL16pay  ! rtpbin.send_rtp_sink_1 rtpbin.send_rtp_src_1 ! udpsink host=192.168.0.255 port=5002 rtpbin.send_rtcp_src_1 ! udpsink host=192.168.168.0.255 port=5003 sync=false async=false udpsrc port=5007 ! rtpbin.recv_rtcp_sink_1';
// 			$command .= ' > /dev/null';
// 			$command .= ' 2>/dev/null';
// 			$command .= ' &';
// // 			$command .= ' echo $';
// 			$result = $this->exec($command);
// 			debug($result);
// 			return true;
// 		}
// 		return false;
// 	}
	
	public function startStreamingVideo($config) {
		$template = $config['streamCommand'];
		$gstCommand = $this->vsprintf_named($template, $config);
		if (!$this->isStreaming()) {
// 			$command = 'python2 /home/remote/stream_video.py';
			$command = $gstCommand;
			$command .= ' > /dev/null';
			$command .= ' 2>/dev/null';
			$command .= ' &';
			// 			$command .= ' echo $';
			$result = $this->exec($command);
			return true;
		}
		return false;
	}
	
	public function stopStreamingVideo() {
		if ($this->isStreaming()) {
// 			$command = 'killall raspivid';
			$command = 'killall gst-launch-1.0';
			$command .= ' > /dev/null';
			$command .= ' 2>/dev/null';
			$command .= ' &';
			// 			$command .= ' echo $';
			$result = $this->exec($command);
			return true;
		}
		return false;
	}
	
	public function reboot() {
		$command = 'sudo reboot';
// 		$command .= ' > /dev/null';
// 		$command .= ' 2>/dev/null';
		$command .= ' &';
		// 			$command .= ' echo $';
		$result = $this->exec($command);
// 		debug($result);
		return $result;
	}
	
	public function shutdown() {
		$command = 'sudo shutdown -h 0';
// 		$command .= ' > /dev/null';
// 		$command .= ' 2>/dev/null';
		$command .= ' &';
		// 			$command .= ' echo $';
		$result = $this->exec($command);
// 		debug($result);
		return $result;
	}
	
	function vsprintf_named($format, $args) {
		$names = preg_match_all('/%\((.*?)\)/', $format, $matches, PREG_SET_ORDER);
	
		$values = array();
		foreach($matches as $match) {
			$values[] = $args[$match[1]];
		}
	
		$format = preg_replace('/%\((.*?)\)/', '%', $format);
		return vsprintf($format, $values);
	}
}