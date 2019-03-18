<?php
namespace kjBotPlugin\GroupCheck\Common;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

class _Message extends Plugin {
    public $handleDepth = 1; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function message($event) {
        $Queue[] = $this->checkLifeQestion($event);
        $Queue[] = $this->checkLovely($event);
        $Queue[] = $this->checkSupport($event);
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
        if ($this->hasMsg($event,'赞助') && !($this->hasMsg($event,'查看'))) {
            $msg = '';
            if ($event->fromGroup()) $msg .= CQCode::At($event->getId())."\n";
            $msg .= '如想赞助请联系 '. CQCode::At(Config('master')) . "\n";
            $msg .= '如想查看赞助人员，请输入 `查看赞助`';
            return $event->sendBack($msg);
        }
        return NULL;
    }

    private function hasMsg($event,string $msg) {
        return (false !== strpos($event->getMsg(), $msg));
    }

}