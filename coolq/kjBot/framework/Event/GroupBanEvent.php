<?php
namespace kjBot\Framework\Event;

class GroupBanEvent extends NoticeEvent{
    use GroupEvent;
    public function __construct($obj){
        parent::__construct($obj);
        $this->groupId = $obj->group_id;
    }
}
