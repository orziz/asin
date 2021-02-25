<?php

namespace kjBotModule\Ninepm;

use kjBot\Framework\TargetType;
use kjBot\SDK\CoolQ;
use kjBot\SDK\CQCode;
use kjBotModule\Ninepm\Data;
use kjBotModule\Ninepm\Brand;

class Action {

    public function init($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state > 0) q('游戏已开始');
        Data::setDataByKey('state', 1);
        Data::setDataByKey('admin', $event->getId());
        Data::setDataByKey('brands', Brand::getBrand());
        Data::setDataByKey('users', array());
        Data::setDataByKey('ausers', array());
        return $event->sendBack('21点开启成功，玩家可加入游戏，由 '.CQCode::At($event->getId()).' 坐庄');
    }

    public function join($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state !== 1) q('游戏正在进行或没有人坐庄，不能加入');
        $users = Data::getDataByKey('users', array());
        $admin = Data::getDataByKey('admin');
        if ($event->getId() === $admin) q('庄家无需加入游戏');
        if (in_array($event->getId(), $users)) q('您已加入，无需重复加入');
        if (count($users) >= 9) q('人数已达10人上限，不可再加入');
        array_push($users, $event->getId());
        Data::setDataByKey('users', $users);
        return $event->sendBack(CQCode::At($event->getId()).' 您已成功加入，请耐心等待庄家开始');
    }

    public function start($event) {
        $state = Data::getDataByKey('state', 0);
        $admin = Data::getDataByKey('admin');
        if (!$admin) q('没有庄家，不能开始游戏');
        if ($admin !== $event->getId()) q('你不是庄家，不能开始游戏');
        if ($state !== 1) q('游戏正在进行或没有人坐庄，不能开始');
        $users = Data::getDataByKey('users', array());
        if (count($users) < 1) q('人数不足，无法开启游戏，请至少等待一位玩家');
        Data::setDataByKey('state', 2);
        Data::setDataByKey('cuser', array('user'=>$admin));
        $Queue[] = $event->sendBack('游戏开始成功，由庄家 '.CQCode::At($admin).' 先摸牌');
        $Queue = array_merge($Queue, $this->newCuser($event));
        return $Queue;
    }

    public function need($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state !== 2) q('游戏未开始');
        $cuser = Data::getDataByKey('cuser')['user'];
        $admin = Data::getDataByKey('admin');
        $msg = CQCode::At($event->getId());
        if ($event->getId() !== $cuser) return $event->sendBack($msg.' 当前不是您的回合');
        $oc = Brand::sendBrand();
        $msg .= ' 您本次抽牌为：'.Brand::id2text($oc);
        $cb = Brand::getCuserBrand();
        $v = 0;
        foreach ($cb as $value) {
            $v += $value['value'];
        }
        if ((($cuser !== $admin) && $v > 21) || count($cb) >= 5) {
            if (($cuser !== $admin) && $v > 21) {
                $Queue[] = $event->sendBack($msg."\n您已爆牌");
            } else {
                $Queue[] = $event->sendBack($msg."\n您已抽够五张牌");
            }
            $cu = Brand::changeUser();
            if ($cu !== false) {
                $Queue[] = $event->sendBack('接下来是 '. CQCode::At($cu). ' 的回合');
                $Queue = array_merge($Queue, $this->newCuser($event));
            } else {
                $Queue[] = $event->sendBack($this->end($event));
                // $Queue = array_merge($Queue, $this->end($event));
            }
            return $Queue;
        }
        return $event->sendBack($msg);
    }

    public function pass($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state !== 2) q('游戏未开始');
        $cuser = Data::getDataByKey('cuser')['user'];
        $msg = CQCode::At($event->getId());
        if ($event->getId() !== $cuser) return $event->sendBack($msg.' 当前不是您的回合');
        $cu = Brand::changeUser();
        if ($cu !== false) {
            $Queue[] = $event->sendBack('接下来是 '. CQCode::At($cu). ' 的回合');
            $Queue = array_merge($Queue, $this->newCuser($event));
        } else {
            $Queue[] = $event->sendBack($this->end($event));
            // $Queue = array_merge($Queue, $this->end($event));
        }
        return $Queue;
    }

    public function help($event) {
$msg = <<<EOF
.21 坐庄 -> 庄家坐庄，仅游戏未开始时使用
.21 开始 -> 庄家开始，仅庄家坐庄后可使用，仅庄家可用
.21 参加 -> 玩家加入游戏，仅庄家坐庄后可使用
.21 要 -> 玩家摸牌，仅当前回合玩家可用
.21 过 -> 玩家过牌，仅当前回合玩家可用，然后进入下一玩家回合或游戏结束
.21 庄家 -> 查看当前庄家
.21 玩家 -> 查看当前玩家
.21 状态 -> 查看当前状态
.21 规则 -> 查看牌型大小规则
EOF;
        return $event->sendBack($msg);
    }

    public function rule($event) {
$msg = <<<EOF
玩法规则：
由一名玩家成为庄家（坐庄），然后其他玩家加入（参加），等到所有玩家加入后，庄家开始游戏。

发牌规则：
从庄家开始，先发一张暗牌及一张明牌，由玩家自行选择是（要）还是（过），当前玩家过了之后，进入下一玩家，以此循环。待所有玩家均过之后，游戏结算。
超过21点或已抽满五张牌会自动过。

牌型比较规则：
黑杰克（A+10/J/Q/K）
五小龙（抽满五张且总点数不大于21）
点数（点数比大小）
爆牌（超过21点）
EOF;
        return $event->sendBack($msg);
    }

    public function showAdmin($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state === 0) q('当前没有人坐庄');
        $user = Data::getDataByKey('admin');
        return $event->sendBack('当前庄家为：'.CQCode::At($user));
    }

    public function showCUser($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state !== 2) q('游戏未开始');
        $user = Data::getDataByKey('cuser')['user'];
        return $event->sendBack('当前庄家为：'.CQCode::At($user));
    }

    public function showState($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state === 0) $s = '空闲';
        if ($state === 1) $s = '准备中';
        if ($state === 2) $s = '游戏中';
        return $event->sendBack('当前游戏状态为：'.$s);
    }

    private function newCuser($event) {
        $cu = Data::getDataByKey('cuser')['user'];
        $dc = Brand::sendBrand();
        $oc = Brand::sendBrand();
        $Queue[] = $event->sendTo(TargetType::Private, $cu, '您本次抽卡为：'.Brand::id2text($dc));
        $Queue[] = $event->sendBack(CQCode::At($cu). ' 暗牌已私聊给您，如未收到请先添加小不语为好友并给小不语发一个"你好"');
        $Queue[] = $event->sendBack(CQCode::At($cu). ' 您本次牌面为：'.Brand::id2text($oc));
        $cb = Brand::getCuserBrand();
        $v = 0;
        foreach ($cb as $value) {
            $v += $value['value'];
        }
        if ($v > 21) {
            $Queue[] = $event->sendBack(CQCode::At($cu)." 您已爆牌");
            $chu = Brand::changeUser();
            if ($chu !== false) {
                $Queue[] = $event->sendBack('接下来是 '. CQCode::At($chu). ' 的回合');
                $Queue = array_merge($Queue, $this->newCuser($event));
            } else {
                $Queue[] = $event->sendBack($this->end($event));
                // $Queue = array_merge($Queue, $this->end($event));
            }
        }
        return $Queue;
    }

    private function end($event) {
        $ausers = Data::getDataByKey('ausers', array());
        Data::setDataByKey('state', 0);
        $msg = '游戏结束，本次各玩家牌面为：';
        foreach ($ausers as $key => $value) {
            $msg .= "\n".CQCode::At($key) . ' 牌面为：';
            $i = $n = 0;
            $as = [];
            foreach ($value as $k => $v) {
                if ($i !== 0) $msg .= '+';
                $msg .= $v['text'];
                if (in_array($k, array('1','14','27','40'))) {
                    array_push($as, 'A');
                } else {
                    $n += $v['value'];
                }
                $i++;
            }
            if (count($as) > 0) $n .= '+' . implode('+', $as);
            $msg .= '，总计：'.$n;
            // $Queue[] = $event->sendBack($msg);
        }
        return $msg;
        // return $Queue;
    }

}