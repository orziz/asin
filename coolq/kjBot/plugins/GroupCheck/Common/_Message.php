<?php
namespace kjBotPlugin\GroupCheck\Common;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\TargetType;
use \Log;

class _Message extends Plugin {
    public $handleDepth = 1; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function message($event) {
        global $Plugins;
        $Queue[] = $this->checkLifeQestion($event);
        $Queue[] = $this->checkLovely($event);
        $Queue[] = $this->checkSupport($event);
        $Queue[] = $this->eatWhat($event);
        $Queue[] = $this->checkSeason2($event);
        $Queue[] = $this->checkCallRGZZ($event);
        // $Queue[] = $this->sscheck($event);
        // $Queue[] = $this->forwardAsin($event);
        return $Queue;
    }

    /**
     * 监听询问生命的意义
     * @param [type] $event
     * @return void
     */
    private function checkLifeQestion($event) {
        if ($this->hasMsg($event,'生命') && $this->hasMsg($event,'意义') && $this->hasMsg($event,'什么')) {
            return $event->sendBack('是42！');
        }
        if ($this->hasMsg($event,"what's the meaning of life?")) {
            return $event->sendBack('是42！');
        }
        return NULL;
    }

    /**
     * 监听询问生命的意义
     * @param [type] $event
     * @return void
     */
    private function checkCallRGZZ($event) {
        if ($this->hasMsg($event,'人工智障')) {
            $eventList = [
                CQCode::At($event->getId()) . ' 你才是智障！你全家都是智障！'
                , '谁在叫我？'
                , CQCode::At($event->getId()) . ' 我在呢我在呢！别叫了！'
                , CQCode::At($event->getId()) . ' 狗再叫'
                , '命令……命令……命令解析失败，系统……唔……系统即将……系统即将爆炸'
                , CQCode::At($event->getId()) . ' 怎么？想小爷了？'
                , CQCode::At($event->getId()) . ' 咋啦？憨批'
                , '子不语说，他是想把我做成“人工智障”。我问他什么是“人工智障”，他说他负责人工，我负责智障……'
                , '再乱叫我让萝卜子咬你！'
                , '叫我有什么用？去找子不语啊，我又不能自己改代码'
                , CQCode::At($event->getId()) . ' 你是不是有什么奇怪的嗜好？'
                , '我不是人工智障，我是人工智障！'
            ];
            return $event->sendBack($eventList[mt_rand(0,count($eventList)-1)]);
        }
        return NULL;
    }

    private function checkLovely($event) {
        if ($this->hasMsg($event,CQCode::At(Config('self_id'))) && $this->hasMsg($event,'可爱') && !($this->hasMsg($event,'不'))) {
            $msg = '';
            if ($event->fromGroup()) $msg .= CQCode::At($event->getId()).' ';
            $eventList = [
                '你也很可爱呢～',
                '毕竟我是吃可爱多长大的～',
                '相比起我，还是你更可爱～'
            ];
            $msg .= $eventList[mt_rand(0,count($eventList)-1)];
            return $event->sendBack($msg);
        }
        return NULL;
    }

    /**
     * 检查是否想要查看赞助信息
     * @param [type] $event
     * @return void
     */
    private function checkSupport($event) {
        if ($this->hasMsg($event,'赞助') && !($this->hasMsg($event,'查看')) && !($this->hasMsg($event,'添加'))) {
            $msg = '';
            if ($event->fromGroup()) $msg .= CQCode::At($event->getId())."\n";
            $msg .= '如想赞助请联系 '. CQCode::At(Config('master')) . "\n";
            $msg .= '如想查看赞助人员，请输入 `查看赞助`';
            $msg .= "\n（备注：所谓赞助为无偿赞助，除标注您的赞助金额之外，不会获得任何其他好处且赞助金额不可返还。如想赞助，请仔细考虑)";
            return $event->sendBack($msg);
        }
        return NULL;
    }

    /**
     * 吃什么鸭
     *
     * @param [type] $event
     * @return void
     */
    private function eatWhat($event) {
        if ($this->hasMsg($event,'吃什么') || $this->hasMsg($event,'吃啥')) {
            $arr = array('炒菜','米线','干锅','金拱门','老娘舅','开封菜','鑫花溪','赛百味','重庆小面','冒菜','麻辣烫');
            $msg = '';
            $User_id = $event->getId();
            if ($event->fromGroup()) $msg .= CQCode::At($User_id)."\n";
            $msg .= '吃 '. $arr[mt_rand(0,count($arr)-1)] .' 吧';
            return $event->sendBack($msg);
        }
        return NULL;
    }

    private function sscheck($event) {
        $ssArr = ['6.4','64','89','6月4号','光腚','广电','闭关锁国','文化局','焚化局','言论自由','GCD','gcd','天朝','6/4','http','url.cn','t.cn','30'];
        for ($i = 0; $i < count($ssArr); $i++) {
            if ($this->hasMsg($event,$ssArr[$i]) && !$this->hasMsg($event,'[CQ:image') && !$this->hasMsg($event,'[CQ:at')) {
                global $kjBot;
                $kjBot->getCoolQ()->deleteMsg($event->msgId);
                break;
            }
        }
        return NULL;
    }

    private function checkSeason2($event) {
        if ($this->hasMsg($event, '第二季')) {
            $msg = '';
            if ($event->fromGroup()) $msg .= CQCode::At($event->getId()) . ' ';
            $msgList = [
                "别问，问就是不知道，再问就禁言",
                "不会百度吗？憨批",
                "注册个微博要钱？"
            ];
            $msg .= $msgList[mt_rand(0,count($msgList)-1)];
            return $event->sendBack($msg);
        }
        return NULL;
    }

    private function forwardAsin($event) {
        if ($event->fromGroup()) {
            if ($event->groupId == '794476874' && $event->getId() == '3593266839') {
                return $event->sendTo(TargetType::Group, '697329381', '鸡大保在管理群说：'.$event->getMsg());
            }
        }
        return NULL;
    }

    private function hasMsg($event,string $msg) {
        return (false !== strpos($event->getMsg(), $msg));
    }

}