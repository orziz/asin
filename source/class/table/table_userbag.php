<?php

/**
 * 
 */
class table_userbag extends C
{
	
	public function __construct() {

		$this->_table = 'asin_userbag';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function getItem($db,$qq) {
		if (!$qq) return false;
		$data = mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE qq=%d",$this->_table, $qq)));
		return ($data ? unserialize($data['bag']) : false);
	}

	public function newItem($db,$qq,$item,$num) {
		if (!$qq) return false;
		$data = $this->getItem($db,$qq);
		if ($data || (!$data && is_array($data))) return $this->updateItem($db,$qq,$item,$num);
		$itemArr = array();
		$itemArr["$item"] = array('name'=>$item,'num'=>intval($num));
		return $db->execute(sprintf("INSERT INTO %s (qq, bag) VALUES (%d,'%s')",$this->_table,$qq,serialize($itemArr)));
	}

	public function updateItem($db,$qq,$item,$num) {
		$data = $this->getItem($db,$qq);
		if (isset($data["$item"])) {
			$data["$item"]['num'] = intval($data["$item"]['num']) + intval($num);
		} else {
			$data["$item"] = array('name'=>$item,'num'=>$num);
		}
		return $db->execute(sprintf("UPDATE %s SET bag='%s' WHERE qq=%d",$this->_table,serialize($data),$qq));
	}

	public function useItem($db,$qq,$item,$num) {
		if (!$qq) return false;
		$data = $this->getItem($db,$qq);
		if (!$data) return 201;
		if (isset($data["$item"])) {
			if (intval($data["$item"]['num']) < intval($num)) return 203;
			$data["$item"]['num'] = intval($data["$item"]['num']) - intval($num);
			if ($data["$item"]['num'] <= 0) unset($data["$item"]);
		} else {
			return 202;
		}
		return $db->execute(sprintf("UPDATE %s SET bag='%s' WHERE qq=%d",$this->_table,serialize($data),$qq));
	}

}