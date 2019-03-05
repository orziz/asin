<?php

/**
 * 
 */
class table_checkin extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_checkin';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	public function getCheckinByDay($day=null,$db=null) {
		if (!$db) global $db;
		if (!$day) $day = getTime('Y-m-d');
		return $db->fetch($this->_table,array('lday'=>$day));
	}

	protected function newData($pk,array $datas,$db) {
		$day = getTime('Y-m-d');
		$datas['count'] = 1;
		$datas['lday'] = $day;
		return parent::newData($pk,$datas,$db);
	}

	protected function updateData($pk,array $datas,$db) {
		$data = $this->getData($qq,$db);
		$yday = getTime('Y-m-d',time()-86400);
		$day = getTime('Y-m-d');
		$count = ($data['lday'] == $yday) ? (int)$data['count'] : 0;
		$count++;
		$datas['count'] = $count;
		$datas['lday'] = $day;
		return parent::updateData($pk,$datas,$db);
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