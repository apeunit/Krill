<?php
App::uses('AppController', 'Controller');
/**
 * Logs Controller
 *
 * @property Log $Log
 * @property PaginatorComponent $Paginator
 */
class LogsController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow('add', 'index');
	}

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
	public $paginate = array(
		'order' => array(
			'Log.created' => 'DESC'
		)
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Paginator->settings = $this->paginate;
		//debug($this->Log->getLastLog(1, 1));
		$conditions = array();
		if (isset($this->request->params['named']['type'])) {
			$types = explode(',', $this->request->params['named']['type']);
			//debug($types);
			$conditions['Log.type'] = $types;
		}
		if (isset($this->request->params['named']['raspi'])) {
			$conditions['Log.raspberry_id'] = $this->request->params['named']['raspi'];
		}
		$this->Log->recursive = 0;
		$this->set('logs', $this->Paginator->paginate('Log', $conditions));
		$this->set('_serialize', array('logs'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Log->exists($id)) {
			throw new NotFoundException(__('Invalid log'));
		}
		$options = array('conditions' => array('Log.' . $this->Log->primaryKey => $id));
		$this->set('log', $this->Log->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'json') {
			//check HMAC
// 			if (isset($this->request->data['hash']) && isset($this->request->data['timestamp'])) {
// 				App::uses('HMAC', 'Lib');
// 				$privateKey = Configure::read('API_KEY');
// 				$timeout = Configure::read('API_TIMEOUT');
// 				$HMAC = new HMAC($privateKey, $timeout);
// 				$check = $HMAC->check($this->request->data['hash'], $this->request->data['timestamp'], $this->request->data['type'], $this->request->data['message']);
// 				if (!$check) {
// 					throw new ForbiddenException(__('HMAC failed'));
// 				}
// 				unset($this->request->data['hash']);
// 				unset($this->request->data['timestamp']);
// 			} else {
// 				throw new ForbiddenException(__('No HMAC data'));
// 			}
			
			$result = array('success' => false);
			if ($this->request->is('post')) {
				$result = $this->Log->save($this->request->data);
			} else {
				throw new MethodNotAllowedException(__('Only POST allowed'));
			}
			$this->set('result', $result);
			$this->set('_serialize', array('result'));
		} else {
			if ($this->request->is('post')) {
				$this->Log->create();
				if ($this->Log->save($this->request->data)) {
					$this->Session->setFlash(__('The log has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The log could not be saved. Please, try again.'));
				}
			}
			$raspberries = $this->Log->Raspberry->find('list');
			$this->set('logTypes', Configure::read('LOG_TYPES'));
			$this->set(compact('raspberries'));
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Log->exists($id)) {
			throw new NotFoundException(__('Invalid log'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Log->save($this->request->data)) {
				$this->Session->setFlash(__('The log has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The log could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Log.' . $this->Log->primaryKey => $id));
			$this->request->data = $this->Log->find('first', $options);
		}
		$raspberries = $this->Log->Raspberry->find('list');
		$this->set(compact('raspberries'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Log->id = $id;
		if (!$this->Log->exists()) {
			throw new NotFoundException(__('Invalid log'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Log->delete()) {
			$this->Session->setFlash(__('The log has been deleted.'));
		} else {
			$this->Session->setFlash(__('The log could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function deleteOld() {
		$this->request->onlyAllow('post', 'delete');
		if ($this->Log->deleteOld()) {
			$this->Session->setFlash(__('Yay'), 'flash_success');
		} else {
			$this->Session->setFlash(__('Nay'), 'flash_error');
		}
		return $this->redirect(array('action' => 'index'));
		
	}
}
