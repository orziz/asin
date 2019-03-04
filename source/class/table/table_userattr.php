<?php

/**
 * 
 */
class userattr extends C
{
	
	function __construct()
	{
		$this->_table = 'asin_userattr';
		$this->_pk = 'qq';

		parent::__construct();
	}

	public function getUserAttr($qq,$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $db->fetch($this->_table,array('qq'=>$qq));
		return $data ? $data[0] : false;
	}

	public function setUserAttr($qq,$str=0,$dex=0,$con=0,$ine=0,$wis=0,$cha=0,$free=0,$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $this->getUserAttr($qq,$db);
		if ($data) return $this->updateUserAttr($qq,$str,$dex,$con,$ine,$wis,$cha,$free,$db);
		return $this->newUserAttr($qq,$str,$dex,$con,$ine,$wis,$cha,$free,$db);
	}

	private function newUserAttr($qq,$str,$dex,$con,$ine,$wis,$cha,$free,$db) {
		return $db->insert($this->_table,array(
			'qq'=>$qq,
			'str'=>(int)$str,
			'dex'=>(int)$dex,
			'con'=>(int)$con,
			'ine'=>(int)$ine,
			'wis'=>(int)$wis,
			'cha'=>(int)$cha,
			'free'=>(int)$free
		));
	}

	private function updateUserAttr($qq,$str,$dex,$con,$ine,$wis,$cha,$free,$db) {
		return $db->update($this->_table,array(
			'str'=>(int)$str,
			'dex'=>(int)$dex,
			'con'=>(int)$con,
			'ine'=>(int)$ine,
			'wis'=>(int)$wis,
			'cha'=>(int)$cha,
			'free'=>(int)$free
		),array('qq'=>$qq));
	}
}