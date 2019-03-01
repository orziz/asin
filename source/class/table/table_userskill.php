<?php

/**
 * 
 */
class table_userskill extends C
{
	
	public function __construct() {

		$this->_table = 'asin_userskill';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	public function getUserSkill($db,$qq) {
		if (!$qq) return false;
		return mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE qq=%d",$this->_table, $qq)));
	}

	public function newUserSkill($db,$qq,$skill1,$skill2,$skill3,$skill4) {
		if (!$qq) return false;
		$data = $this->getUserSkill($db,$qq);
		if ($data) return $this->updateUserSkill($db,$qq,$skill1,$skill2,$skill3,$skill4);
		$time = '2000-01-01 00:00:01';
		return $db->execute(sprintf("INSERT INTO %s (qq, skill1, skill1time, skill2, skill2time, skill3, skill3time, skill4, skill4time) VALUES (%d,'%s','%s','%s','%s','%s','%s','%s','%s')",$this->_table,$qq,$skill1,$time,$skill2,$time,$skill3,$time,$skill4,$time));
	}

	public function updateUserSkill($db,$qq,$skill1,$skill2,$skill3,$skill4) {
		if (!$qq) return false;
		$data = $this->getUserSkill($db,$qq);
		if (!$data) return false;
		$time = '2000-01-01 00:00:01';
		return $db->execute(sprintf("UPDATE %s SET skill1='%s', skill1time='%s', skill2='%s', skill2time='%s', skill3='%s', skill3time='%s', skill4='%s', skill4time='%s' WHERE qq=%d",$this->_table,$skill1,$time,$skill2,$time,$skill3,$time,$skill4,$time,$qq));
	}

	public function setCD($db,$qq,$skill1cd=0,$skill2cd=0,$skill3cd=0,$skill4cd=0) {
		if (!$qq) return false;
		$data = $this->getUserSkill($db,$qq);
		if (!$data) return false;
		return $db->execute(sprintf("UPDATE %s SET skill1cd=%d, skill2cd=%d, skill3cd=%d, skill4cd=%d WHERE qq=%d",$this->_table,intval($skill1cd)*60,intval($skill2cd)*60,intval($skill3cd)*60,intval($skill4cd)*60,$qq));
	}

	public function setUseTime($db,$qq,$type) {
		$data = $this->getUserSkill($db,$qq);
		if (!$data) return false;
		$typecd = $type . 'cd';
		$typetime = $type . 'time';
		date_default_timezone_set('Asia/Shanghai');
		$time = date('Y-m-d H:i:s', time());
		if (strtotime($time) < (strtotime($data[$typetime])+intval($data[$typecd]))) return ((strtotime($data[$typetime])+intval($data[$typecd])) - strtotime($time));
		return $db->execute(sprintf("UPDATE %s SET %s='%s' WHERE qq=%d",$this->_table,$typetime,$time,$qq));
	}

}