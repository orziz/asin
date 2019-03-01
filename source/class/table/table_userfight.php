<?php

/**
 * 
 */
class table_userfight extends C
{
	
	public function __construct() {

		$this->_table = 'asin_userfight';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function getUserFitgh($db,$qq) {
		if (!$qq) return false;
		return mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE qq=%d ORDER BY id DESC",$this->_table, $qq)));
	}

	public function newFight($db,$qq,$pkqq) {
		if (!$qq) return false;
		$data = $this->getUserFitgh($db,$qq);
		if ($data) if (!$data['isend']) return 201;
		$creatDB = $db->execute(sprintf("CREATE TABLE IF NOT EXISTS %s (
			`qq` bigint NOT NULL,
			`atk` int DEFAULT NULL,
			`def` int DEFAULT NULL,
			`bld` int DEFAULT NULL,
			`ats` int DEFAULT NULL,
			`spd` int DEFAULT NULL,
			`hit` int DEFAULT NULL,
			`dge` int DEFAULT NULL,
			`lcy` int DEFAULT NULL,
			`spy` int DEFAULT NULL,
			`lck` int DEFAULT NULL,
			`sek` int DEFAULT NULL,
			`elo` int DEFAULT NULL,
			`str` int DEFAULT NULL,
			`dex` int DEFAULT NULL,
			`con` int DEFAULT NULL,
			`ine` int DEFAULT NULL,
			`wis` int DEFAULT NULL,
			`cha` int DEFAULT NULL,
			`issek` int DEFAULT NULL,
			PRIMARY KEY (`qq`)
		);",('`' . $this->_table . '_' . $qq . '`')));
		if (!$creatDB) return 202;
		$qqinfo = C::t('userinfo')->getUserInfo($db,$qq);
		$qqinfo['atk'] = C::t('userinfo')->getAttrInfo($qqinfo,'atk');
		$qqinfo['def'] = C::t('userinfo')->getAttrInfo($qqinfo,'def');
		$qqinfo['bld'] = C::t('userinfo')->getAttrInfo($qqinfo,'bld');
		$qqinfo['ats'] = C::t('userinfo')->getAttrInfo($qqinfo,'ats');
		$qqinfo['spd'] = C::t('userinfo')->getAttrInfo($qqinfo,'spd');
		$qqinfo['hit'] = C::t('userinfo')->getAttrInfo($qqinfo,'hit');
		$qqinfo['dge'] = C::t('userinfo')->getAttrInfo($qqinfo,'dge');
		$qqinfo['lcy'] = C::t('userinfo')->getAttrInfo($qqinfo,'lcy');
		$qqinfo['spy'] = C::t('userinfo')->getAttrInfo($qqinfo,'spy');
		$qqinfo['lck'] = C::t('userinfo')->getAttrInfo($qqinfo,'lck');
		$qqinfo['sek'] = C::t('userinfo')->getAttrInfo($qqinfo,'sek');
		$qqinfo['elo'] = C::t('userinfo')->getAttrInfo($qqinfo,'elo');
		$dbqq = $db->execute(sprintf("INSERT INTO %s (qq, atk, def, bld, ats, spd, hit, dge, lcy, spy, lck, sek, elo, str, dex, con, ine, wis, cha,issek) VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",($this->_table . '_' . $qq),$qq,intval($qqinfo['atk']),intval($qqinfo['def']),intval($qqinfo['bld']),intval($qqinfo['ats']),intval($qqinfo['spd']),intval($qqinfo['hit']),intval($qqinfo['dge']),intval($qqinfo['lcy']),intval($qqinfo['spy']),intval($qqinfo['lck']),intval($qqinfo['sek']),intval($qqinfo['elo']),intval($qqinfo['str']),intval($qqinfo['dex']),intval($qqinfo['con']),intval($qqinfo['ine']),intval($qqinfo['wis']),intval($qqinfo['cha']),1));
		if (!$dbqq) return 203;
		$pkqqinfo = C::t('userinfo')->getUserInfo($db,$pkqq);
		$pkqqinfo['atk'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'atk');
		$pkqqinfo['def'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'def');
		$pkqqinfo['bld'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'bld');
		$pkqqinfo['ats'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'ats');
		$pkqqinfo['spd'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'spd');
		$pkqqinfo['hit'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'hit');
		$pkqqinfo['dge'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'dge');
		$pkqqinfo['lcy'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'lcy');
		$pkqqinfo['spy'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'spy');
		$pkqqinfo['lck'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'lck');
		$pkqqinfo['sek'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'sek');
		$pkqqinfo['elo'] = C::t('userinfo')->getAttrInfo($pkqqinfo,'elo');
		$dbpkqq = $db->execute(sprintf("INSERT INTO %s (qq, atk, def, bld, ats, spd, hit, dge, lcy, spy, lck, sek, elo, str, dex, con, ine, wis, cha,issek) VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",($this->_table . '_' . $qq),$pkqq,intval($pkqqinfo['atk']),intval($pkqqinfo['def']),intval($pkqqinfo['bld']),intval($pkqqinfo['ats']),intval($pkqqinfo['spd']),intval($pkqqinfo['hit']),intval($pkqqinfo['dge']),intval($pkqqinfo['lcy']),intval($pkqqinfo['spy']),intval($pkqqinfo['lck']),intval($pkqqinfo['sek']),intval($pkqqinfo['elo']),intval($pkqqinfo['str']),intval($pkqqinfo['dex']),intval($pkqqinfo['con']),intval($pkqqinfo['ine']),intval($pkqqinfo['wis']),intval($pkqqinfo['cha']),1));
		if (!$dbqq) return 204;
		date_default_timezone_set('Asia/Shanghai');
		$time = date('Y-m-d H:i:s', time());
		return $db->execute(sprintf("INSERT INTO %s (id, qq, pkqq, isend, begintime) VALUES (%d,%d,%d,%d,'%s')",$this->_table,0,$qq,$pkqq,0,$time));
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

	public function endFight($db,$qq,$winner) {
		// return sprintf("DROP TABLE IF EXISTS %s",('`' . $this->_table . '_' . $qq . '`'));
		// return true;
		// if (!$dropDB) return 201;
		// return true;
		date_default_timezone_set('Asia/Shanghai');
		$time = date('Y-m-d H:i:s', time());
		$data = $this->getUserFitgh($db,$qq);
		$id = $data['id'];
		// return true;
		return $db->execute(sprintf("UPDATE %s SET isend=%d, winner=%d, endtime='%s' WHERE id=%d",$this->_table,1,intval($winner),$time,intval($id)));

		return $db->execute(sprintf("DROP TABLE IF EXISTS %s",('`' . $this->_table . '_' . $qq . '`')));
		// $db->execute(sprintf("UPDATE %s SET isend=%d, winner=%d, endtime='%s' WHERE id=%d",$this->_table,1,intval($winner),$time,intval($id)));
	}

	public function getFightInfo($db,$qqtable,$qq) {
		if (!$qq) return false;
		return mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE qq=%d",($this->_table . '_' . $qqtable),$qq)));
	}

	public function addHurt($db,$qqtable,$qq,$num) {
		$attrInfo = $this->getFightInfo($db,$qqtable,$qq);
		$num = intval($attrInfo['bld']) - intval($num);
		return $db->execute(sprintf("UPDATE %s SET bld=%d WHERE qq=%d",($this->_table . '_' . $qqtable),$num,$qq));
	}

	public function setSek($db,$qqtable,$qq,$num) {
		return $db->execute(sprintf("UPDATE %s SET issek=%d WHERE qq=%d",($this->_table . '_' . $qqtable),intval($num),$qq));
	}

	public function subBld() {

	}

}