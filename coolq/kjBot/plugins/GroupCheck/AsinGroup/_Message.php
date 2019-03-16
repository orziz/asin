<?php
namespace kjBotPlugin\GroupCheck\AsinGroup;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

class _Message extends Plugin {
    public $handleDepth = 3; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function message_group_normal($event) {
        if($event instanceof GroupMessageEvent) {
            $asinGroup = ['719994813','758507034'];
            if (in_array($event->groupId,$asinGroup)) {
                global $Modules;
                if ((false !== strpos($event->getMsg(), '怎么') || false !== strpos($event->getMsg(), '如何')) && false !== strpos($event->getMsg(), '加入')) {
                    $Queue[] = $event->sendBack(CQCode::At($event->getId()).' 如需加入刺客组织，请输入 `加入刺客组织`');
                }
                $Modules['帮助'] = \kjBotModule\Asin\Help::class;
                $Modules['信息'] = \kjBotModule\Asin\Home\Info::class;
                $Modules['属性'] = \kjBotModule\Asin\Home\Attr::class;
                $Modules['排名'] = \kjBotModule\Asin\Rank\GetMyScoreRank::class;
                $Modules['设置信息'] = \kjBotModule\Asin\Home\SetUserInfo::class;
                $Modules['加入刺客组织'] = \kjBotModule\Asin\Join\JoinOrgan::class;
                $Modules['将所有人录入刺客组织'] = \kjBotModule\Asin\Join\AllJoinOrgan::class;
                $Modules['签到'] = \kjBotModule\Asin\Home\Checkin::class;
                $Modules['签到排行榜'] = \kjBotModule\Asin\Rank\CheckinRank::class;
                $Modules['刺客排行榜'] = \kjBotModule\Asin\Rank\ScoreRank::class;
                $Modules['开始刺客大乱斗'] = \kjBotModule\Asin\Act\BeginAsinFight::class;
                $Modules['参加刺客大乱斗'] = \kjBotModule\Asin\Act\JoinAsinFight::class;

                $Queue[] = $this->randomEvent($event);
                return $Queue;
            }
        }
        return NULL;
    }

    private function randomEvent($event) {
        $rand = mt_rand(0,10000);
        if ($rand >= 9900 || (false !== strpos($event->getMsg(), '子不语牛逼') && $rand >= 9000)) {
            $score = mt_rand(0,2);
            $credit = mt_rand(1,200);
            $data = param_post('http://asin.ygame.cc/api.php',array('mod' => 'home_userscore', 'action'=>'add', 'qq'=>$event->getId(), 'score'=>$score,'credit'=>$credit));
            if ($data && $data['errCode'] === 200) {
                $eventArr = array(
                    '扶老奶奶过马路，',
                    '帮伍六七卖牛杂，',
                    '帮梅花十三捡飞镖，',
                    '帮鸡大保系领带，',
                    '帮忙照看小飞，',
                    '帮梅花十三梳辫子，',
                    '帮柒修理千刃，',
                    '帮子不语找BUG，',
                    '帮小岛主任整治不文明现象，',
                    '帮可乐完成心愿，',
                    '帮阿婆接小龙放学，',
                    '帮可乐完成心愿，',
                    '帮猫小咪送信给汪疯，',
                    '帮海军部长巡逻，',
                    '帮居委会大妈巡逻，',
                    '帮内裤男找漂亮内裤，'
                );
                $msg = CQCode::At($event->getId());
                $msg .= ' '.$eventArr[mt_rand(0,count($eventArr)-1)];
                $msg .= '获得';
                if ($score > 0) $msg .= ' ' . $score . '积分，';
                $msg .= ' ' . $credit . '暗币';
                return $event->sendBack($msg);
            }
        }
        return NULL;
    }

}