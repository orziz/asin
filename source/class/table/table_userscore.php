<?php

/**
 * 
 */
class table_userscore extends C
{
	
	public function __construct() {

		$this->_table = 'asin_userscore';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	public function getUserScore($db,$qq) {
		if (!$qq) return false;
		return mysql_fetch_array($db->execute(sprintf("SELECT * FROM %s WHERE qq=%d",$this->_table, $qq)));
	}

	/**
	 * 根据 qq 获取排名
	 * @param  [type] $db [description]
	 * @param  [type] $qq [description]
	 * @return [type]     [description]
	 */
	public function getRank($db,$qq) {
		if (!$qq) return false;
		$data = $this->getUserScore($db,$qq);
		if (!$data) return false;
		if ($data['rank']) return $data['rank'];
		if (intval($data['score']) > 300) {
			$rank = $db->execute(sprintf("SELECT count(qq) AS count FROM %s WHERE score > %d OR (score = %d AND utime < '%s')", $this->_table, intval($data['score']), intval($data['score']), $data['utime']));
			$rank = mysql_fetch_array($rank)['count'];
			$rank = intval($rank) + 11001;
		} elseif (intval($data['score']) > 200) {
			$rank = $db->execute(sprintf("SELECT count(qq) AS count FROM %s WHERE score < %d AND score > %d OR (score = %d AND utime < '%s')", $this->_table, 301, intval($data['score']), intval($data['score']), $data['utime']));
			$rank = mysql_fetch_array($rank)['count'];
			$rank = intval($rank) + 13001;
		} elseif (intval($data['score']) > 100) {
			$rank = $db->execute(sprintf("SELECT count(qq) AS count FROM %s WHERE score < %d AND score > %d OR (score = %d AND utime < '%s')", $this->_table, 201, intval($data['score']), intval($data['score']), $data['utime']));
			$rank = mysql_fetch_array($rank)['count'];
			$rank = intval($rank) + 15001;
		} else {
			$rank = $db->execute(sprintf("SELECT count(qq) AS count FROM %s WHERE score < %d AND score > %d OR (score = %d AND utime < '%s')", $this->_table, 101, intval($data['score']), intval($data['score']), $data['utime']));
			$rank = mysql_fetch_array($rank)['count'];
			$rank = intval($rank) + 17501;
		}
		return $rank;
	}

	/**
	 * 获取排行榜列表
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	public function getRankList($db) {
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
	 * 设置用户积分数据
	 * @param  [type] $db       [description]
	 * @param  [type] $qq       qq号/唯一标识
	 * @param  [type] $nickname 昵称
	 * @param  [type] $score    积分
	 * @param  [type] $rank     排行（非指定即为0）
	 * @return [type]           [description]
	 */
	public function setUserScore($db,$qq,$nickname,$score,$rank) {
		if (!$qq) return false;
		$data = $this->getUserScore($db,$qq);
		if ($data) return $this->updateUserScore($db,$qq,$nickname,$score,$rank);
		return $this->newUserScore($qq,$nickname,$score,$rank,$qq);
	}

	/**
	 * 私有方法 新增用户积分数据
	 * @param  [type] $qq       qq号/唯一标识
	 * @param  [type] $nickname 昵称
	 * @param  [type] $score    积分
	 * @param  [type] $rank     排行（非指定即为0）
	 * @param  [type] $db       [description]
	 * @return [type]           [description]
	 */
	private function newUserScore($qq,$nickname,$score,$rank,$db=null) {
		$time = date('Y-m-d H:i:s', time());
		return $db->insert($this->_table,array(
			'qq'=>$qq,
			'nickname'=>$nickname,
			'score'=>intval($score),
			'rank'=>intval($rank),
			'ctime'=>$time,
			'utime'=>$time
		));
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
	private function updateUserScore($qq,$nickname,$score,$rank,$db=null) {
		$time = date('Y-m-d H:i:s', time());
		return $db->update($this->_table,array(
			'nickname'=>$nickname,
			'score'=>$score,
			'rank'=>$rank,
			'utime'=>$time
		),array('qq'=>$qq));
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