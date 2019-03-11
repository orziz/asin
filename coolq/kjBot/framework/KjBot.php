<?php
namespace kjBot\Framework;

use kjBot\SDK\CoolQ;
use \Log;

class KjBot{

    private $cq;
    protected $selfId;
    protected $messageQueue = [];

    public function __construct(CoolQ $coolQ, $id = NULL){
        $this->cq = $coolQ;
        $this->selfId = $id;
    }

    public function getCoolQ(){
        return $this->cq;
    }

    /**
     * 添加消息
     *
     * @param array<Message>|Message $msg
     * @return void
     */
    public function addMessage($msg){
        Log::Debug('让我看看这是什么类型：：：'.gettype($msg));
        if($msg instanceof Message){
            $this->messageQueue[]= $msg;
        }else if(is_array($msg)){
            Log::Debug('是数组哦～');
            array_merge($this->messageQueue, $msg);
        }else if($msg === NULL){
            return;
        }else{
            throw new \Exception("Can't add message: ".\export($msg));
        }
    }

    public function postMessage(){
        foreach($this->messageQueue as $message){
            if($message instanceof Message){
                $message->send($this->cq);
            }
        }
    }

    public function &getMessageQueue(){
        return $this->messageQueue;
    }
}
