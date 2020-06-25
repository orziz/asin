<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;

/** 洗点 */
class ResetAttr extends Module
{
	
	public function process(array $args, $event){
        $msg = '';
        if (isset($args[1])) checkAuth($event);
		$atqq = isset($args[1]) ? parseQQ($args[1]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id)."\n";
        }
        $dAttr = new \Domain\UserAttr();
        return $event->sendBack('这里错了？');
        $resetAttr = $dAttr->resetAttr($qq);
        if ($resetAttr === -1) {
            $msg .= '洗点失败：您没有加入刺客组织';
        } elseif (!$resetAttr) {
            $msg .= '洗点失败';
        } else {
            $userAttr = $dAttr->getUserAttrWithFight($qq);
            $msg .= "洗点成功，当前属性点为：\n";
            $msg .= '力量：'.$userAttr['str']."\n";
            $msg .= '敏捷：'.$userAttr['dex']."\n";
            $msg .= '体质：'.$userAttr['con']."\n";
            $msg .= '智力：'.$userAttr['ine']."\n";
            $msg .= '感知：'.$userAttr['wis']."\n";
            $msg .= '魅力：'.$userAttr['cha']."\n";
            $msg .= '自由属性点：'.$userAttr['free']."\n\n";
            $msg .= '血量上限（大乱斗）：'.$userAttr['maxBld']."\n";
            $msg .= '攻击力（大乱斗）：'.$userAttr['atk']."\n";
            $msg .= '暴击率（大乱斗）：'.$userAttr['crit']." %";
        }
        return $event->sendBack($msg);
    }
}