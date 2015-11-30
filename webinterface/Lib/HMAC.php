<?php

/**
 * This class simplifies the creation and checking of Hash-based message authentication codes
 * This can be used to make REST-APIs a bit more secure
 * 
 * @author Max Doerfler (max@muxe.org)
 *
 */
class HMAC {
	
	/**
	 * Holds the private key. This should never be sent but only be used to create the hash
	 * @var string
	 */
	private $privateKey = null;
	/**
	 * Time in seconds until a request is considered expired. This should prevent replay attacks
	 * @var int
	 */
	private $timeout = null;
	
	/**
	 * Constructor, accepts a private key and optinally a timeout time
	 * 
	 * @param string $privateKey the private key
	 * @param int $timeout timeout in seconds
	 */
	function __construct($privateKey, $timeout = 5) {
		$this->privateKey = $privateKey;
		$this->timeout = $timeout;
	}
	
	/**
	 * Used Serverside to check if a request is valid.
	 * 
	 * @param string $checkHash the submitted hash from the client to compare with
	 * @param int $timestamp the submitted unix timestamp or null if you don't want to compare times
	 * @param mixed $param1 from this on, all params are added to the the hash in the order given.
	 * @return boolean true if the request was valid, else false
	 */
	public function check($checkHash, $timestamp = null, $param1 = null) {
		$args = func_get_args();
		$data = '';
		//add all serialized params to the data string
		for ($i = 2; $i < sizeof($args); $i++) {
			$data .= $args[$i];
		}
		if ($timestamp) {
			//check if the request is expired
			if (abs(time() - $timestamp) > $this->timeout) {
				return false;
			}
			//add the timestamp to the data string (so no man in the middle can modify this)
			$data .= $timestamp;
		}
		//finally add the private key
		$data .= $this->privateKey;
		//mix well
		$hash = hash('sha256', $data);
		return $checkHash === $hash;
	}
	
	/**
	 * Used clientside to build a hash to send to the server
	 * 
	 * @param mixed $timestamp either a unix timestamp or null to use the current time() or false to not add a timestamp at all
	 * @param mixed $param1 from this on, all params are added to the the hash in the order given.
	 * @return string
	 */
	public function generate($timestamp = null, $param1 = null) {
		$args = func_get_args();
		$data = '';
		for ($i = 1; $i < sizeof($args); $i++) {
			$data .= $args[$i];
		}
		if ($timestamp === null) {
			$timestamp = time();
		}
		if ($timestamp !== false) {
			$data .= $timestamp;
		}
		$data .= $this->privateKey;
		debug($data);
		$hash = hash('sha256', $data);
		return $hash;
	}
}
