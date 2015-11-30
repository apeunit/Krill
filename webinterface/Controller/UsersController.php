<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login', 'logout');
	}
	
	public function login() {
		if (AuthComponent::user()) {
			$this->redirect('/');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Falsche Login-Daten'));
			}
		}
	}
	
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
}
