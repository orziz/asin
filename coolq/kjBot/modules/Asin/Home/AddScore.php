<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看用户属性
 */
class AddScore extends Module
{
	
	public function process(array $args, $event){
        checkAuth($event);
        $msg = '';
        if (!isset($args[1])) q('修改失败：未知字段名（可用字段名为 积分/暗币）');
        if (!isset($args[2])) q('加点失败：未指定点数');
        $args[2] = intval($args[2]);
		$atqq = isset($args[3]) ? parseQQ($args[3]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event->fromGroup()) $msg .= CQCode::At($User_id)."\n";
        $obj = array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$qq);
        switch ($args[1]) {
            case '积分':
                $obj['score'] = $args[2];
                break;
            case '暗币':
                $obj['credit'] = $args[2];
                break;
            default:
                q('修改失败：未知字段名（可用字段名为 积分/暗币）');
                break;
        }
        $data = param_post('http://asin.ygame.cc/api.php',$obj);
        if ($data['errCode'] !== 200) {
            $msg .= '修改失败：';
            if ($data['errCode'] === 301) $msg .= '您没有加入刺客组织';
            if ($data['errCode'] === 302) $msg .= '修改失败';
            if ($data['errCode'] === 303) $msg .= '自由属性点不足';
        } else {
            $msg .= "增加 {$args[1]} {$args[2]} 点成功，当前信息为：\n";
            $userScore = $data['data'];
            $msg .= '积分：'.($userScore['score'] < 0 ? '？？？' : $userScore['score'])."\n";
            $msg .= '暗币：'.$userScore['credit'];
        }
        return $event->sendBack($msg);
    }
}