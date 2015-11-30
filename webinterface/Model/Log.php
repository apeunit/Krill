<?php
App::uses('AppModel', 'Model');
/**
 * Log Model
 * Stores Logs from the Clients in the database
 *
 * @property Raspberry $Raspberry
 */
class Log extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'message' => array(
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

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Raspberry' => array(
			'className' => 'Raspberry',
			'foreignKey' => 'raspberry_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function beforeValidate($options = array()) {
		if (!isset($this->data['Log']['raspberry_id']) && isset($this->data['Log']['serial'])) {
			//only serial given, get raspberry ID by serial
			$raspberry = $this->Raspberry->getOrCreate($this->data['Log']['serial']);
			if (isset($raspberry['Raspberry']['id'])) {
				$this->data['Log']['raspberry_id'] = $raspberry['Raspberry']['id'];
			}
			unset($this->data['Log']['serial']);
		}
		return true;
	}

	public function getLastLog($raspiID = null, $type = null) {
		$options = array(
			'conditions' => array(),
			'order' => 'Log.created DESC'
		);
		if ($raspiID) {
			$options['conditions']['Log.raspberry_id'] = $raspiID;
		}
		if (isset($type)) {
			$options['conditions']['Log.type'] = $type;
		}
		return $this->find('first', $options);
	}

	public function deleteOld() {
		return $this->deleteAll(array(
			'Log.created < DATE_SUB(NOW(), INTERVAL 2 DAY)',
		));
	}
}
