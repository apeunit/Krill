<?php 
App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class SuperSimpleAuthenticate extends BaseAuthenticate {
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$password = isset($request->data['User']['password']) ? $request->data['User']['password'] : '';
		
		if ($this->checkPassword($password)) {
			return array('loggedin' => true);
		}
		return false;
	}
	
	public function getUser(CakeRequest $request) {
		if ($request->is('post') && isset($request->data['password'])) {
			$password = $request->data['password'];
			if ($this->checkPassword($password)) {
				return array('loggedin' => true);
			} else {
				throw new ForbiddenException(__('Wrong Password'));
			}
		}
		return false;
	}
	
	private function checkPassword($password) {
		$controllPasswords = Configure::read('PASSWORDS');
		if (empty($controllPasswords)) {
			return false;
		}
		if (in_array(AuthComponent::password($password), $controllPasswords)) {
// 		if (AuthComponent::password($password) == $controllPassword) {
			return array('loggedin' => true);
		}
		return false;
	}
}