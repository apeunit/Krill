<?php
App::uses('AppModel', 'Model');
/**
 * Raspberry Model
 * This is the Client Model, it represents one Camera/Raspberry Module.
 * It offers methods to control the stream on the Clients
 *
 * @property Log $Log
 */
class Raspberry extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'serial' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'raspberry_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Config' => array(
			'className' => 'Config',
			'foreignKey' => 'raspberry_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function addRaspberry() {
		App::uses('HMAC', 'Lib');
		$privateKey = Configure::read('API_KEY');
		$timeout = Configure::read('API_TIMEOUT');
		$HMAC = new HMAC($privateKey, $timeout);
	}

	public function getOrCreate($serial, $ip = '', $name = 'AutoCreate') {
		$raspberry = $this->find('first', array('conditions' => array('serial' => $serial)));
		if (!$raspberry) {
			$newRaspData = array(
				'Raspberry' => array(
					'name' => $name,
					'serial' => $serial,
					'ip' => $ip,
				),
			);
			$this->create();
			$raspberry = $this->save($newRaspData);
		}
		return $raspberry;
	}

	public function isStreaming($raspiID) {
		//get the IP
		$raspberry = $this->findById($raspiID);
		if (isset($raspberry['Raspberry']['ip']) && !empty($raspberry['Raspberry']['ip'])) {
			$ip = $raspberry['Raspberry']['ip'];
			App::uses('MySSH', 'Lib');
			$SSH = new MySSH($ip, Configure::read('SSHCONF.user'), Configure::read('SSHCONF.password'), Configure::read('SSHCONF.port'), Configure::read('SSHCONF.keyfile'));
			return $SSH->isStreaming();
		}
		return false;
	}

	public function startStreamingVideo($raspiID) {
		$this->contain(array('Config'));
		$raspberry = $this->findById($raspiID);
		if (isset($raspberry['Raspberry']['ip']) && !empty($raspberry['Raspberry']['ip'])) {
			try {
				$ip = $raspberry['Raspberry']['ip'];
				App::uses('MySSH', 'Lib');
				$SSH = new MySSH($ip, Configure::read('SSHCONF.user'), Configure::read('SSHCONF.password'), Configure::read('SSHCONF.port'), Configure::read('SSHCONF.keyfile'));
				$config = $this->Config->mergeDefaults($raspberry['Config']);
				$config = $this->Config->buildSprintArray($config);
				return $SSH->startStreamingVideo($config);
			} catch (Exception $e) {
				return false;
			}
		}
		return false;
	}

	public function stopStreamingVideo($raspiID) {
		$raspberry = $this->findById($raspiID);
		if (isset($raspberry['Raspberry']['ip']) && !empty($raspberry['Raspberry']['ip'])) {
			try {
				$ip = $raspberry['Raspberry']['ip'];
				App::uses('MySSH', 'Lib');
				$SSH = new MySSH($ip, Configure::read('SSHCONF.user'), Configure::read('SSHCONF.password'), Configure::read('SSHCONF.port'), Configure::read('SSHCONF.keyfile'));
				return $SSH->stopStreamingVideo();
			} catch (Exception $e) {
				return false;
			}
		}
		return false;
	}

	public function reboot($raspiID) {
		$raspberry = $this->findById($raspiID);
		if (isset($raspberry['Raspberry']['ip']) && !empty($raspberry['Raspberry']['ip'])) {
			$ip = $raspberry['Raspberry']['ip'];
			App::uses('MySSH', 'Lib');
			$SSH = new MySSH($ip, Configure::read('SSHCONF.user'), Configure::read('SSHCONF.password'), Configure::read('SSHCONF.port'), Configure::read('SSHCONF.keyfile'));
			return $SSH->reboot();
		}
		return false;
	}

	public function shutdown($raspiID) {
		$raspberry = $this->findById($raspiID);
		if (isset($raspberry['Raspberry']['ip']) && !empty($raspberry['Raspberry']['ip'])) {
			$ip = $raspberry['Raspberry']['ip'];
			App::uses('MySSH', 'Lib');
			$SSH = new MySSH($ip, Configure::read('SSHCONF.user'), Configure::read('SSHCONF.password'), Configure::read('SSHCONF.port'), Configure::read('SSHCONF.keyfile'));
			return $SSH->shutdown();
		}
		return false;
	}
}
