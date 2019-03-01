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

	public function getCheckin($db,$qq) {
		if (!$qq) return false;
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time());
		$data = $this->getCheckinByDay($db,$day);
		if (!$data) return false;
		return ($data["$qq"] ? $data["$qq"] : false);
	}

	public function getCheckinByDay($db,$day) {
		if (!$day) return false;
		$data = mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE day='%s'",$this->_table, $day)));
		return ($data ? unserialize($data['member']) : false);
	}

	public function newDay($db,$qq) {
		if (!$qq) return false;
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time());
		$time = date('Y-m-d H:i:s', time());
		$data = $this->getCheckinByDay($db,$day);
		if ($data) return $this->updateDay($db,$qq);
		$cont = $this->getCont($db,$qq);
		$cont = intval($cont) + 1;
		$checkinArr = [];
		$checkinArr["$qq"] = array('cont'=>$cont,'time'=>$time);
		return $db->execute(sprintf("INSERT INTO %s (day, member) VALUES ('%s','%s')",$this->_table,$day,serialize($checkinArr)));
	}

	public function updateDay($db,$qq) {
		if (!$qq) return false;
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time());
		$time = date('Y-m-d H:i:s', time());
		$data = $this->getCheckinByDay($db,$day);
		if (!$data) return $this->newDay($db,$qq);
		if ($data["$qq"]) return 201;
		$cont = $this->getCont($db,$qq);
		$cont = intval($cont) + 1;
		$data["$qq"] = array('cont'=>$cont,'time'=>$time);
		return $db->execute(sprintf("UPDATE %s SET member='%s' WHERE day='%s'",$this->_table,serialize($data),$day));
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