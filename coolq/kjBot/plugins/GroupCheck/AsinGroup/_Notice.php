<?php

namespace kjBotPlugin\GroupCheck\AsinGroup;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupIncreaseEvent;
use kjBot\SDK\CQCode;
use \Log;

class _Notice extends Plugin {

	public $handleDepth = 2; //捕获到最底层的事件
	public $handleQueue = true; //声明是否要捕获消息队列

	const cq_notice_group_increase = true;

	public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
	//此处以正常群聊消息举例
	public function coolq_notice_group_increase($event,$cq): ?Message{
		Log::Debug('捕获到加群事件');
		$asinGroup = ['719994813','758507034'];
		if (in_array($event->groupId,$asinGroup)) {
			Log::Debug('确定为刺客群');
			$msg = CQCode::At($event->getId())."\n";
            $memberInfo = $cq->getGroupMemberInfo($event->groupId,$event->getId());
            Log::Debug('开始提交数据');
            $data = param_post('http://asin.ygame.cc/api.php',array(
                'mod' => 'home_userinfo',
                'action'=>'newUserInfo',
                'qq'=>$memberInfo->user_id,
                'nickname'=>$memberInfo->nickname,
                'age'=>$memberInfo->age,
                'sex'=>0,
                'height'=>170,
                'weight'=>50,
                'str'=>20,
                'dex'=>20,
                'con'=>20,
                'ine'=>20,
                'wis'=>20,
                'cha'=>20,
                'free'=>20,
                'arms'=>'',
                'introduce'=>'此人太过神秘，暂时没有相关信息',
                'skill1'=>'',
                'skill2'=>'',
                'skill3'=>'',
                'skill4'=>'',
                'score'=>0,
                'credit'=>0,
                'rank'=>0
            ));
            Log::Debug('获取到数据了');
            Log::Debug('查看 errCode：'.$data['errCode']);
            if ($data['errCode'] === 200) {
            	$msg .= $memberInfo->nickname.' ，刺客组织欢迎您的加入，您目前的排名为 '.$data['data']['rank']." ，请努力提高排名吧！\n";
            } elseif ($data['errCode'] === 301) {
            	$msg .= $memberInfo->nickname." ，欢迎您回到刺客组织\n";
            } else {
            	Log::Error('Coolq JoinOrgan===> qq：'.$event->getId().' errCode：'.$data['errCode'].' errMsg：'.$data['errMsg']);
            }
            $msg .= "请修改群名片为 省份-昵称，请仔细阅读群公告，构建和谐群聊";
            return $event->sendBack($msg);
		}
		return NULL;
	}
}