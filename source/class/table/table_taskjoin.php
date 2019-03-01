<?php

/**
 * 
 */
class table_taskjoin extends C
{
	
	public function __construct() {

		$this->_table = 'asin_taskjoin';
		$this->_pk    = 'day';

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

	public function getDayInfo($db,$day) {
		if (!$day) return false;
		return mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE day='%s'",$this->_table, $day)));
	}

	public function newDayTask($db,$time,$maxnum) {
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time());
		$data = $this->getDayInfo($db,$day);
		if ($data) return $this->updateDayTask($db,$time,$maxnum);
		$member = array();
		return $db->execute(sprintf("INSERT INTO %s (day,maxmember,begintime,member) VALUES ('%s',%d,'%s','%s')",$this->_table,$day,intval($maxnum),$time,serialize($member)));
	}

	public function updateDayTask($db,$time,$maxnum) {
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time());
		$data = $this->getDayInfo($db,$day);
		if (!$data) return $this->newDayTask($db,$time,$maxnum);
		return $db->execute(sprintf("UPDATE %s SET maxmember=%d,begintime='%s' WHERE day='%s'",$this->_table,intval($maxnum),$time,$day));
	}

	public function joinTask($db,$qq) {
		date_default_timezone_set('Asia/Shanghai');
		$day = date('Y-m-d', time());
		$data = $this->getDayInfo($db,$day);
		if (!$data) return 201;
		$begintime = explode(':', $data['begintime']);
		$nowtime = date('H:i', time());
		$nowtime = explode(':', $nowtime);
		$bH = intval($begintime[0]);
		$bi = intval($begintime[1]);
		$nH = intval($nowtime[0]);
		$ni = intval($nowtime[1]);
		$member = unserialize($data['member']);
		if (in_array($qq, $member)) return 202;
		if (count($member) >= intval($data['maxmember'])) return 203;
		if (($nH > $bH) || (($nH == $bH) && ($ni >= $bi))) {
			array_push($member, $qq);
			return $db->execute(sprintf("UPDATE %s SET member='%s' WHERE day='%s'",$this->_table,serialize($member),$day));
		} else {
			return $data['begintime'];
		}
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