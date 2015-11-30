<?php
App::uses('AppController', 'Controller');
/**
 * Raspberries Controller
 *
 * @property Raspberry $Raspberry
 * @property Config $Config
 * @property Local $Local
 * @property PaginatorComponent $Paginator
 */
class RaspberriesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
	public $uses = array('Raspberry', 'Local');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = array(
			'contain' => array(
				'Log' => array(
					'conditions' => array('Log.type' => 2),
					'order' => 'Log.created DESC',
					'limit' => 1,
				)
			)
		);
		$raspberries = $this->Paginator->paginate('Raspberry');
// 		debug($raspberries);
		$this->set('raspberries', $raspberries);
		$this->set('_serialize', array('raspberries'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Raspberry->exists($id)) {
			throw new NotFoundException(__('Invalid raspberry'));
		}
		$this->Raspberry->contain(array(
// 			'Log',
			'Config',
		));
		$options = array('conditions' => array('Raspberry.' . $this->Raspberry->primaryKey => $id));
		$raspberry = $this->Raspberry->find('first', $options);
		if (isset($raspberry['Config'])) {
			$raspberry['Config'] = $this->Raspberry->Config->mergeDefaults($raspberry['Config']);
		}
		$this->set('raspberry', $raspberry);
		$this->set('_serialize', array('raspberry'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Raspberry->create();
			if ($this->Raspberry->save($this->request->data)) {
				$this->Session->setFlash(__('The raspberry has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The raspberry could not be saved. Please, try again.'));
			}
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Raspberry->id = $id;
		if (!$this->Raspberry->exists()) {
			throw new NotFoundException(__('Invalid raspberry'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Raspberry->delete()) {
			$this->Session->setFlash(__('The raspberry has been deleted.'));
		} else {
			$this->Session->setFlash(__('The raspberry could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		if (!$this->Raspberry->exists($id)) {
			throw new NotFoundException(__('Invalid Raspberry'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Raspberry->save($this->request->data)) {
				$this->Session->setFlash(__('The Raspberry has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Raspberry could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Raspberry.' . $this->Raspberry->primaryKey => $id));
			$this->request->data = $this->Raspberry->find('first', $options);
		}
	}
	
	public function changeIP() {
		$result = array('success' => false);
		if ($this->request->is('post')) {
			$serial = isset($this->request->data['serial']) ? $this->request->data['serial'] : '';
			$ip = isset($this->request->data['ip']) ? $this->request->data['ip'] : '';
			$hostname = isset($this->request->data['hostname']) ? $this->request->data['hostname'] : '';
			$raspberry = $this->Raspberry->getOrCreate($serial);
			if($raspberry) {
				unset($raspberry['Raspberry']['modified']);
				$raspberry['Raspberry']['ip'] = $ip;
				if (!empty($hostname)) {
					$raspberry['Raspberry']['name'] = $hostname;
				}
				$result = $this->Raspberry->save($raspberry);
			}
			//$result = $this->Log->save($this->request->data);
		} else {
			throw new MethodNotAllowedException(__('Only POST allowed'));
		}
		$this->set('result', $result);
		$this->set('_serialize', array('result'));
	}
	
	public function isStreaming($raspiID) {
		$this->request->onlyAllow('ajax');
		$result = array(
			'content' => false,
			'error' => null,
		);
		try {
			$content = $this->Raspberry->isStreaming($raspiID);
			$result['content'] = $content;
		} catch (Exception $e) {
			$result['error'] = 'SSH Error';
		}
		$this->set('result', $result);
		$this->set('_serialize', array('result'));
	}
	
	public function startStreamingVideo($raspiID) {
		$this->request->onlyAllow('post', 'delete');
		$result = $this->Raspberry->startStreamingVideo($raspiID);
		$this->Session->setFlash($result);
		return $this->redirect(array('controller' => 'raspberries', 'action' => 'index'));
	}
	
	public function stopStreamingVideo($raspiID) {
		$this->request->onlyAllow('post');
		$result = $this->Raspberry->stopStreamingVideo($raspiID);
		$this->Session->setFlash($result);
		return $this->redirect(array('controller' => 'raspberries', 'action' => 'index'));
	}
	
	public function reboot($raspiID) {
		$this->request->onlyAllow('post');
		$result = $this->Raspberry->reboot($raspiID);
		$this->Session->setFlash($result);
		return $this->redirect(array('controller' => 'raspberries', 'action' => 'index'));
	}
	
	public function shutdown($raspiID) {
		$this->request->onlyAllow('post');
		$result = $this->Raspberry->shutdown($raspiID);
		debug($result);
		$this->Session->setFlash($result);
		return $this->redirect(array('controller' => 'raspberries', 'action' => 'index'));
	}
	
	public function startAllStreams() {
		$this->request->onlyAllow('post');
		$raspberries = $this->Raspberry->find('all');
		$count = 0;
		foreach ($raspberries as $raspberry) {
			if ($this->Raspberry->startStreamingVideo($raspberry['Raspberry']['id'])) {
				$count++;
			}
		}
		$this->Session->setFlash(__('Started the Stream on %s Raspberries', $count));
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'index')));
	}
	
	public function stopAllStreams() {
		$this->request->onlyAllow('post');
		$raspberries = $this->Raspberry->find('all');
		$count = 0;
		foreach ($raspberries as $raspberry) {
			if ($this->Raspberry->stopStreamingVideo($raspberry['Raspberry']['id'])) {
				$count++;
			}
		}
		$this->Session->setFlash(__('Stopped the Stream on %s Raspberries', $count));
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'index')));
	}
	
	public function startLocalAudio() {
		$this->request->onlyAllow('post');
		if ($this->Local->startLocalAudio()) {
			$this->Session->setFlash(__('Started Audio'), 'flash_success');
		} else {
			$this->Session->setFlash(__('Problem starting Audio'), 'flash_error');
		}
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'index')));
	}
	
	public function startLocalStream() {
		$this->request->onlyAllow('post');
		if ($this->Local->startLocalStream()) {
			$this->Session->setFlash(__('Started Stream'), 'flash_success');
		} else {
			$this->Session->setFlash(__('Problem starting Stream'), 'flash_error');
		}
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'index')));
	}
	
	public function stopLocalAudio() {
		$this->request->onlyAllow('post');
		if ($this->Local->stopLocalAudio()) {
			$this->Session->setFlash(__('Stopped Audio'), 'flash_success');
		} else {
			$this->Session->setFlash(__('Problem stopping Audio'), 'flash_error');
		}
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'index')));
	}
	
	public function stopLocalStream() {
		$this->request->onlyAllow('post');
		if ($this->Local->stopLocalStream()) {
			$this->Session->setFlash(__('Stopped Stream'), 'flash_success');
		} else {
			$this->Session->setFlash(__('Problem stopping Stream'), 'flash_error');
		}
		return $this->redirect($this->referer(array('controller' => 'raspberries', 'action' => 'index')));
	}
	
	public function easy() {
		$isStreaming = $this->Local->isStreaming();
		$isStreamingAudio = $this->Local->isStreamingAudio();
		$this->set('isStreaming', $isStreaming);
		$this->set('isStreamingAudio', $isStreamingAudio);
	}
	
	public function livestream() {
		$iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
		
		$isApple = $iPod || $iPhone || $iPad;
		$this->set('isApple', $isApple);
	}
}
