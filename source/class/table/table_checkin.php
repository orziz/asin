<?php

/**
 * 
 */
class table_checkin extends C
{
	
	public function __construct() {

		$this->_table = 'asin_checkin';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	public function getCheckin($qq,$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $db->fetch($this->_table,array('qq'=>$qq));
		return $data ? $data[0] : false;
	}

	public function getCheckinByDay($day=null,$db=null) {
		if (!$db) global $db;
		if (!$day) $day = getTime('Y-m-d');
		return $db->fetch($this->_table,array('lday'=>$day));
	}

	public function setUserCheckin($qq,$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $this->getCheckin($qq,$db);
		if ($data) return $this->updateUserCheckIn($qq,$db);
		return $this->newUserCheckIn($qq,$db);
	}

	private function newUserCheckin($qq,$db) {
		$day = getTime('Y-m-d');
		return $db->insert($this->_table,array(
			'qq'=>$qq,
			'count'=>1,
			'lday'=>$day
		))
	}

	private function updateUserCheckin($qq,$db) {
		$data = $this->getCheckin($qq,$db);
		$yday = getTime('Y-m-d',time()-86400);
		$day = getTime('Y-m-d');
		$count = ($data['lday'] == $yday) ? (int)$data['count'] : 0;
		$count++;
		return $db->update($this->_table,array(
			'count'=>$count,
			'lday'=$day
		),array('qq'=>$qq));
	}

	public function getCont($db,$qq) {
		if (!$qq) return 0;
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time()-86400);
		$data = $this->getCheckinByDay($db,$day);
		if (!$data) return 0;
		return ($data["$qq"] ? $data["$qq"]['cont'] : 0);
	}

}