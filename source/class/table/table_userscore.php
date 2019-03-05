<?php

/**
 * 
 */
class table_userscore extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_userscore';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	/**
	 * 根据 qq 获取排名
	 * @param  [type] $db [description]
	 * @param  [type] $qq [description]
	 * @return [type]     [description]
	 */
	public function getRank($qq,$db=null) {
		if (!$db) global $db;
		if (!$qq) return false;
		$data = $this->getData($qq,$db);
		if (!$data) return false;
		if ($data['rank']) return $data['rank'];
		$rank = $db->fetch($this->_table,'scorerank > '.$data['scorerank'],'count(qq) AS count');
		$rank = $rank[0]['count'];
		$rank = (int)$rank + 1;
		$rank = $rank + 17365;
		return $rank;
	}

	/**
	 * 获取排行榜列表
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	public function getRankList($db=null) {
		if (!$db) global $db;
		$scoreArr = $db->fetch('asin_userinfo a JOIN '.$this->_table.' b ON a.qq = b.qq');
		$rankList = [];
		for ($i = 0; $i < count($scoreArr); $i++) {
			$data = $scoreArr[$i];
			Log::Debug(json_encode($data));
			$data['rank'] = $this->getRank($db,$data['qq']);
			array_push($rankList, $data);
		}
		array_multisort(array_column($rankList,'rank'),SORT_ASC,$rankList);
		return $rankList;
	}

	/**
	 * 私有方法 新增用户积分数据
	 * @param  [type] $qq       qq号/唯一标识
	 * @param  [type] $score    积分
	 * @param  [type] $rank     排行（非指定即为0）
	 * @param  [type] $db       [description]
	 * @return [type]           [description]
	 */
	protected function newData($pk,array $data,$db) {
		$score = (int)$data['score'];
		$data['scorerank'] = $score*10000000000+(10000000000-time());
		return parent::newData($pk,$data,$db);
	}

	/**
	 * 私有方法 更新用户积分数据
	 * @param  [type] $qq       qq号/唯一标识
	 * @param  [type] $nickname 昵称
	 * @param  [type] $score    积分
	 * @param  [type] $rank     排行（非指定即为0）
	 * @param  [type] $db       [description]
	 * @return [type]           [description]
	 */
	protected function updateData($pk,array $data,$db) {
		$score = (int)$data['score'];
		$data['scorerank'] = $score*10000000000+(10000000000-time());
		return parent::updateUserScore($pk,$data,$db);
	}

	public function add($db,$qq,$type,$num) {
		if (!$qq) return false;
		$data = $this->getUserScore($db,$qq);
		if (!$data) return false;
		date_default_timezone_set('Asia/Shanghai');
		$time = date('Y-m-d H:i:s', time());
		$num = max(intval($data["$type"] ? $data["$type"] : 0) + intval($num),0);
		if ($type == 'score') {
			return $db->execute(sprintf("UPDATE %s SET score=%d, utime='%s' WHERE qq=%d",$this->_table,intval($num),$time,$qq));
		} else {
			return $db->execute(sprintf("UPDATE %s SET credit=%d WHERE qq=%d",$this->_table,intval($num),$qq));
		}
	}

	public function sub($db,$qq,$type,$num) {
		if (!$qq) return false;
		$data = $this->getUserScore($db,$qq);
		if (!$data) return false;
		if (intval($data["$type"] ? $data["$type"] : 0) < intval($num)) return false;
		date_default_timezone_set('Asia/Shanghai');
		$time = date('Y-m-d H:i:s', time());
		$num = max(intval($data["$type"] ? $data["$type"] : 0) - intval($num),0);
		if ($type == 'score') {
			return $db->execute(sprintf("UPDATE %s SET score=%d, utime='%s' WHERE qq=%d",$this->_table,intval($num),$time,$qq));
		} else {
			return $db->execute(sprintf("UPDATE %s SET credit=%d WHERE qq=%d",$this->_table,intval($num),$qq));
		}
	}

	public function updateCheckDay($db,$qq,$day) {
		return $db->execute(sprintf("UPDATE %s SET checkday=%d WHERE qq=%d",$this->_table,intval($day),$qq));
	}

	public function getCheckDayRank($db,$num) {
		return $db->execute(sprintf("SELECT * FROM %s ORDER BY checkday DESC LIMIT 0, %d",$this->_table,$num));
	}

}