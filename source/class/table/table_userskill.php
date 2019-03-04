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

	public function getUserSkill($qq,$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $db->fetch($this->_table,array('qq'=>$qq));
		return $data ? $data[0] : 0;
	}

	public function setUserSkill($qq,$skill1='',$skill2='',$skill3='',$skill4='',$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $this->getUserSkill($qq,$db);
		if ($data) return $this->updateUserSkill($qq,$skill1,$skill2,$skill3,$skill4,$db);
		return $this->newUserSkill($qq,$skill1,$skill2,$skill3,$skill4,$db);
	}

	private function newUserSkill($qq,$skill1,$skill2,$skill3,$skill4,$db) {
		return $db->insert($this->_table,array(
			'qq'=>$qq,
			'skill1'=>$skill1,
			'skill2'=>$skill2,
			'skill3'=>$skill3,
			'skill4'=>$skill4
		));
	}

	private function updateUserSkill($qq,$skill1,$skill2,$skill3,$skill4,$db) {
		return $db->update($this->_table,array(
			'qq'=>$qq,
			'skill1'=>$skill1,
			'skill2'=>$skill2,
			'skill3'=>$skill3,
			'skill4'=>$skill4
		),array('qq'=>$qq));
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