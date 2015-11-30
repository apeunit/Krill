<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	public $components = array(
		'Auth' => array(
			'authenticate' => array('SuperSimple'),
		),
		'Session',
		'RequestHandler'
	);
	
	public $helpers = array('Krikkit');
	
	public function beforeFilter() {
		parent::beforeFilter();
// 		debug(AuthComponent::password(''));
// 		debug($this->request->data);
// 		if ($this->request->is('post') && isset($this->request->data['User']['password'])) {
// 			$this->Auth->login();
// 		}
	}
	
	public function beforeRender() {
		parent::beforeRender();
		$this->setDefaultFlashElement();
	}
	
	private function setDefaultFlashElement($element = 'flash_warning') {
		if ($this->Session->check('Message.flash')) {
			$flash = $this->Session->read('Message.flash');
			if ($flash['element'] == 'default') {
				$flash['element'] = $element;
				$this->Session->write('Message.flash', $flash);
			}
		}
	}
}
