<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看用户属性
 */
class Attr extends Module
{
	
	public function process(array $args, $event){
		$msg = '';
		$atqq = isset($args[1]) ? parseQQ($args[1]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id)."\n";
        }
        $data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userattr', 'action'=>'getUserAttr', 'qq'=>$qq));
        if ($data['errCode'] !== 200) {
            $msg .= '查询失败：';
            if ($qq == $User_id) $msg .= '您没有加入刺客组织';
            else $msg .= '您所查询的对象没有加入刺客组织';
        } else {
            // 力量
            // $str = getgpc('str','param',0);
            // // 敏捷
            // $dex = getgpc('dex','param',0);
            // // 体质
            // $con = getgpc('con','param',0);
            // // 智力
            // $ine = getgpc('ine','param',0);
            // // 感知
            // $wis = getgpc('wis','param',0);
            // // 魅力
            // $cha = getgpc('cha','param',0);
            // // 自由属性点
            // $free = getgpc('free','param',0);
            $userAttr = $data['data'];
            Log::Debug('===>'.json_encode($userAttr));
            $msg .= '姓名：'.$userAttr['nickname']."\n";
            $msg .= '力量：'.$userAttr['str']."\n";
            $msg .= '敏捷：'.$userAttr['dex']."\n";
            $msg .= '体质：'.$userAttr['con']."\n";
            $msg .= '智力：'.$userAttr['ine']."\n";
            $msg .= '感知：'.$userAttr['wis']."\n";
            $msg .= '魅力：'.$userAttr['cha']."\n";
            $msg .= '自由属性点：'.$userAttr['free'];
        }
        return $event->sendBack($msg);
    }
}