<?php
App::uses('AppModel', 'Model');
/**
 * Config Model
 * To store config values for the different clients
 *
 * @property Raspberry $Raspberry
 */
class Config extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'raspberry_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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

	public function mergeDefaults($configs) {
// 		debug($configs);
		$defaults = Configure::read('Raspberry.defaults');
		foreach ($defaults as $key => $defaultValue) {
			$found = false;
			foreach ($configs as $key2 => $config) {
				if ($config['key'] == $key) {
					$configs[$key2]['default'] = $defaultValue;
					$found = true;
					break;
				}
			}
			if (!$found) {
				$configs[] = array(
					'key' => $key,
					'value' => '',
					'default' => $defaultValue,
				);
			}
		}
// 		debug($configs);
		return $configs;
	}

	public function buildSprintArray($configs) {
		$result = array();
		foreach ($configs as $config) {
			if (isset($config['value']) && !empty($config['value'])) {
				$result[$config['key']] = $config['value'];
			} else if (isset($config['default'])) {
				$result[$config['key']] = $config['default'];
			}
		}
		return $result;
	}
}
