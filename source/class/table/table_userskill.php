<?php

/**
 * 
 */
class table_userskill extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_userskill';
		$this->_pk    = 'qq';

		parent::__construct();
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