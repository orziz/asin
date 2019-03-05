<?php

/**
 * 
 */
class table_userinfo extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_userinfo';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	private function newData($pk,array $data,$db) {
		$time = getTime();
		$data['ctime'] = $time;
		$data['utime'] = $time;
		return parent::newData($pk,$data,$db);
	}

	private function updateData($pk,array $data,$db) {
		$time = getTime();
		$data['utime'] = $time;
		return parent::updateData($pk,$data,$db);
	}


	public function add($db,$qq,$type,$num) {
		if (!$qq) return false;
		$data = $this->getUserInfo($db,$qq);
		if (!$data) return false;
		if ($type != 'free') {
			if (intval($data['free']) < intval($num)) return false;
			$freesub = intval($data['free']) - intval($num);
			$num = intval($data["$type"]) + intval($num);
			return $db->execute(sprintf("UPDATE %s SET %s=%d, free=%d WHERE qq=%d",$this->_table,$type,intval($num),intval($freesub),$qq));
		} else {
			$num = intval($data["$type"]) + intval($num);
			return $db->execute(sprintf("UPDATE %s SET %s=%d WHERE qq=%d",$this->_table,$type,intval($num),$qq));
		}
	}

	public function clear($db,$qq,$type,$subscore) {
		if (!$qq) return false;
		$data = $this->getUserInfo($db,$qq);
		if (!$data) return false;
		if ($type && $type != 'all') {
			if (intval($data["$type"]) == 0) return 201;
		} elseif (!$type || $type == 'all') {
			if (intval($data['str']) == 0 && intval($data['dex']) == 0 && intval($data['con']) == 0 && intval($data['ine']) == 0 && intval($data['wis']) == 0 && intval($data['cha']) == 0) return 201;
		}
		$score = C::t('userscore')->sub($db,$qq,'score',$subscore);
		if (!$score) return false;
		if (!$type || $type == 'all') {
			$free = intval($data['free']) + intval($data['str']) + intval($data['dex']) + intval($data['con']) + intval($data['ine']) + intval($data['wis']) + intval($data['cha']);
			return $db->execute(sprintf("UPDATE %s SET free=%d, str=%d, dex=%d, con=%d, ine=%d, wis=%d, cha=%d WHERE qq=%d",$this->_table,intval($free),0,0,0,0,0,0,$qq));
		} else {
			$free = intval($data['free']) + intval($data["$type"]);
			return $db->execute(sprintf("UPDATE %s SET free=%d, %s=%d WHERE qq=%d",$this->_table,intval($free),$type,0,$qq));
		}
	}

	public function setNickName($qq,$nickname,$db=null) {
		if (!$db) global $db;
		$data = $this->getUserInfo($db,$qq);
		if (!$data) return 301;
		return $db->update($this->_table,array('nickname'=>$nickname),array('qq'=>$qq));
	}

	public function getAttrInfo($info,$type) {
		if (!$info) return false;
		switch ($type) {
			case 'atk':
			case '攻击':
				$num = intval(50 + ($info['str'] * 1.75) + ($info['ine'] * 0.2));
				//攻击 = 50 + 力量 * 1.75 + 智力 * 0.2
				break;
			case 'def':
			case '防御';
				$num = intval(30 + ($info['str'] * 0.22) + ($info['con'] * 1.3));
				//防御 = 30 + 力量 * 0.22 + 体质 * 1.3
				break;
			case 'bld':
			case '血量':
				$num = intval(400 + ($info['con'] * 5.8));
				//血量 = 200 + 体质 * 1.68
				//血量 = 400 + 体质 * 5.8
				break;
			case 'ats':
			case '攻速':
				$num = intval(5 + ($info['dex'] * 0.55));
				//攻速 = 20 + 敏捷 * 0.55
				//攻速 = 5 + 敏捷 * 0.55
				break;
			case 'spd':
			case '移速':
				$num = intval(10 + ($info['dex'] * 0.235));
				//移速 = 100 + 敏捷 * 0.235
				//移速 = 10 + 敏捷 * 0.235
				break;
			case 'hit':
			case '命中':
				$num = intval(10 + ($info['dex'] * 0.135) + ($info['wis'] * 0.625));
				//命中 = 100 + 敏捷 * 0.135 + 感知 * 0.625
				//命中 = 10 + 敏捷 * 0.135 + 感知 * 0.625
				break;
			case 'dge':
			case '闪避':
				$num = intval(10 + ($info['dex'] * 0.275) + ($info['wis'] * 0.725));
				//闪避 = 100 + 敏捷 * 0.275 + 感知 * 0.725
				//闪避 = 10 + 敏捷 * 0.275 + 感知 * 0.725
				break;
			case 'lcy':
			case '幸运':
				$num = intval(10 + ($info['cha'] * 0.645));
				//幸运 = 10 + 魅力 * 0.645
				break;
			case 'spy':
			case '侦查':
				$num = intval(($info['ine'] * 0.821) + ($info['wis'] * 0.857));
				//侦查 = 智力 * 1.321 + 感知 * 1.357
				//侦查 = 智力 * 0.821 + 感知 * 0.857
				break;
			case 'lck':
			case '巧手':
				$num = intval(($info['dex'] * 0.305) + ($info['wis'] * 1.37));
				//开锁 = 敏捷 * 0.335 + 感知 * 2.37
				//开锁 = 敏捷 * 0.305 + 感知 * 1.37
				break;
			case 'pik':
				$num = intval(($info['dex'] * 1.195) + ($info['wis'] * 1.207));
				//扒窃 = 敏捷 * 1.195 + 感知 * 1.207
				break;
			case 'sek':
			case '潜行':
				$num = intval(($info['dex'] * 1.095) + ($info['wis'] * 0.57));
				//潜行 = 敏捷 * 1.595 + 感知 * 0.77
				//潜行 = 敏捷 * 1.095 + 感知 * 0.57
				break;
			case 'elo':
			case '口才':
				$num = intval(($info['ine'] * 0.905) + ($info['wis'] * 0.405) + ($info['cha'] * 0.365));
				//口才 = 智力 * 1.375 + 感知 * 0.515 + 魅力 * 0.885
				//口才 = 智力 * 0.905 + 感知 * 0.405 + 魅力 * 0.365
				break;
			default:
				break;
		}
		return $num;
	}

}