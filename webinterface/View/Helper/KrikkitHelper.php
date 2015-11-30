<?php
App::uses('AppHelper', 'View/Helper');

class KrikkitHelper extends AppHelper {
	
	public $helpers = array('Html', 'Time');
	
	public function showLastPing($date) {
		if ($this->Time->wasWithinLast('5 minutes', $date)) {
			return '<span class="label label-success">'.$this->Time->timeAgoinWords($date).'</span>';
		}
		return '<span class="label label-default">'.$this->Time->timeAgoinWords($date).'</span>';
	}
}