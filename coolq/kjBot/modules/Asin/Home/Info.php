<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看用户信息
 */
class Info extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$atqq = isset($args[1]) ? parseQQ($args[1]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id)."\n";
        }
        // $data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userinfo', 'action'=>'getUserInfo', 'qq'=>$qq));
        // if ($data['errCode'] !== 200) {
        //     $msg .= $data['errMsg'];
        // } else {
        //     $userInfo = $data['data'];
        //     $msg .= '姓名：'.$userInfo['nickname']."\n";
        //     $msg .= '排名：'.$userInfo['rank']."\n";
        //     $msg .= '积分：'.($userInfo['score'] < 0 ? '？？？' : $userInfo['score'])."\n";
        //     $msg .= '暗币：'.$userInfo['credit']."\n";
        //     $msg .= '年龄：'.$userInfo['age']."\n";
        //     $msg .= '性别：'.($userInfo['sex'] === 0 ? '未知' : ($userInfo['sex'] === 1 ? '男' : '女'))."\n";
        //     $msg .= '身高：'.$userInfo['height']." cm\n";
        //     $msg .= '体重：'.$userInfo['weight']." kg\n";
        //     $msg .= '介绍：'.$userInfo['introduce']."\n";
        //     $msg .= '加入组织时间：'.$userInfo['ctime'];
        // }
        // $msg .= "\n（该功能由 ".CQCode::At(1845896706).' 赞助）';
        $data = request_get('http://13567.org/plugin.php',array(
            'id' => 'ph_dztojson:ph_dztojson',
            'ccode' => '971109',
            'mod' => 'user_user',
            'action' => 'getUserInfo',
            'qq' => $qq
        ));
        Log::Debug($data);
        if (!$data) {
            $msg .= '请求数据失败';
        } else {
            $data = json_decode($data, true);
            if ($data['code'] === 200) {
                $userInfo = $data['data'];
                $msg .= '姓名：'.$userInfo['username']."\n";
                // $msg .= '排名：'.$userInfo['rank']."\n";
                $msg .= '称号：'.$userInfo['grouptitle']."\n";
                $msg .= '积分：'.$userInfo['credits']."\n";
                $msg .= '声望：'.$userInfo['userCount']['extcredits1']."\n";
                $msg .= '暗币：'.$userInfo['userCount']['extcredits2']."\n";
                // $msg .= '年龄：'.$userInfo['age']."\n";
                $msg .= '性别：'.($userInfo['userProfile']['gender'] === 0 ? '未知' : ($userInfo['sex'] === 1 ? '男' : '女'))."\n";
                // $msg .= '身高：'.$userInfo['height']." cm\n";
                // $msg .= '体重：'.$userInfo['weight']." kg\n";
                $msg .= '介绍：'.$userInfo['userFieldForum']['sightml']."\n";
                $msg .= '加入组织时间：'. getTime('Y-m-d H:i:s', $userInfo['regdate']);
            } else {
                $msg .= $data['errMsg'];
            }
        }
        return $event->sendBack($msg);
    }
}