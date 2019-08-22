<?php

/**
 * 签到相关数据库操作
 */
class table_checkin extends Table
{
	
	public function __construct() {

		$this->_table = 'asin_checkin';
		$this->_pk    = 'qq';

		parent::__construct();
	}

	/**
	 * 根据日期获取签到信息
	 * @param  [type] $day 需要获取签到信息的日期
	 * @return [type]      [description]
	 */
	public function getCheckinByDay($day=null) {
		if (!$day) $day = getTime('Y-m-d');
		return $this->fetch(array('lday'=>$day));
	}

	/**
	 * 新建用户签到信息
	 * @param  [type] $pk    [description]
	 * @param  array  $datas [description]
	 * @return [type]        [description]
	 */
	protected function newData($pk,array $datas) {
		$num = 10000000000;
		$day = getTime('Y-m-d');
		$datas['count'] = 1;
		$datas['countrank'] = $datas['count']*$num+($num-time());
		$datas['lday'] = $day;
		return parent::newData($pk,$datas);
	}

	/**
	 * 更新用户签到信息
	 * @param  [type] $pk    [description]
	 * @param  array  $datas [description]
	 * @return [type]        [description]
	 */
	protected function updateData($pk,array $datas) {
		$data = $this->getData($pk);
		$yday = getTime('Y-m-d',time()-86400);
		$day = getTime('Y-m-d');
		$count = ($data['lday'] == $yday) ? (int)$data['count'] : 0;
		$count++;
		$num = 10000000000;
		$datas['count'] = $count;
		$datas['countrank'] = $datas['count']*$num+($num-time());
		$datas['lday'] = $day;
		return parent::updateData($pk,$datas);
	}

	/**
	 * 检测今天是否已签到
	 * @param  [type]  $pk [description]
	 * @return [type]      [description]
	 */
	public function isCheckin($pk) {
		if (!$pk) return false;
		$data = $this->getData($pk);
		if (!$data) return false;
		return ($data['lday'] == getTime('Y-m-d'));
	}

	/**
	 * 获取排行榜列表
	 * @param  integer $limit 获取数量
	 * @return [type]         [description]
	 */
	public function getRankList($limit=0) {
		if ($limit) return $this->fetch(null,'*','countrank DESC',0,$limit);
		return $this->fetch(null,'*','countrank DESC');
	}

	/**
	 * 清空某人签到信息
	 *
	 * @param [type] $qq
	 * @return void
	 */
	public function cleanDataByQQ($qq) {
		return $this->update(array('count'=>0,'countrank'=>0),array('qq'=>$qq));
	}

}