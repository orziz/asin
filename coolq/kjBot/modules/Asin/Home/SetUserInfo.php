<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 设置用户昵称
 */
class SetUserInfo extends Module
{
	
	public function process(array $args, $event){
        checkAuth($event,'admin');
        $User_id = $event->getId();
		$msg = '';
        if (!isset($args[1])) q('未输入修改项');
        if (!isset($args[2])) q('未输入修改值');
        $type = $args[1];
        $value = $args[2];
		$atqq = isset($args[3]) ? parseQQ($args[3]) : null;
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent) $msg .= CQCode::At($User_id)."\n";
        $datas = array(
            'mod' => 'home_userinfo',
            'action'=>'getUserInfo',
            'qq'=>$qq
        );
        switch ($type) {
            case '昵称':
                $datas['nickname'] = $value;
                break;
            case '性别':
                $datas['sex'] = $value;
                break;
            case '年龄':
                $datas['age'] = $value;
                break;
            case '身高':
                $datas['height'] = $value;
                break;
            case '体重':
                $datas['weight'] = $value;
                break;
            case '体重':
                $datas['weight'] = $value;
                break;
            case '武器':
                $value = implode("\n",explode('\n',$value));
                $datas['arms'] = $value;
                break;
            case '介绍':
                $value = implode("\n",explode('\n',$value));
                $datas['introduce'] = $value;
                break;
            default:
                q('没有该项目');
                break;
        }
        $data = param_post('http://asin.ygame.cc/api.php',$datas);
        if ($data['errCode'] !== 200) {
            $msg .= $data['errMsg'];
        } else {
            $msg .= '已将 '.CQCode::At($qq).' 的 '.$type.' 设置为 '.$value;
        }
        return $event->sendBack($msg);
    }
}