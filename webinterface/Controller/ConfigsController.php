<?php
App::uses('AppController', 'Controller');
/**
 * Configs Controller
 *
 * @property Raspberry $Raspberry
 * @property Config $Config
 */
class ConfigsController extends AppController {
	public function setConfig() {
		if ($this->request->is(array('post', 'put'))) {
			$raspberryID = isset($this->request->data['Config']['raspberry_id']) ? $this->request->data['Config']['raspberry_id'] : null;
			$key = isset($this->request->data['Config']['key']) ? $this->request->data['Config']['key'] : null;
			$value = isset($this->request->data['Config']['value']) ? $this->request->data['Config']['value'] : null;
			//find old value
			$config = $this->Config->find('first', array('conditions' => array(
				'Config.raspberry_id' => $raspberryID,
				'Config.key' => $key,
			)));
			if ($config) {
				$config['Config']['value'] = $value;
				unset($config['Config']['modified']);
			} else {
				$config = array('Config' => array(
					'raspberry_id' => $raspberryID,
					'key' => $key,
					'value' => $value,
				));
				$this->Config->create();
			}
			$this->Config->save($config);
		}
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'view', $raspberryID)));
	}
	
	public function delete($id) {
		$this->Config->id = $id;
		if (!$this->Config->exists()) {
			throw new NotFoundException(__('Invalid config'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Config->delete()) {
			$this->Session->setFlash(__('The config has been deleted.', 'flash_success'));
		} else {
			$this->Session->setFlash(__('The config could not be deleted. Please, try again.'));
		}
		return $this->redirect($this->referer('/'));
	}
}
