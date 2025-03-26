<?php
namespace tinymeng\getui\request\user;

use tinymeng\getui\request\GTApiRequest;

class GTTagSetRequest extends GTApiRequest{
    //用户标识
    private $cid;
    //标签列表，标签中不能包含空格
    private $customTag;

    public function getCid()
    {
        return $this->cid;
    }

    public function setCid($cid)
    {
        $this->cid = $cid;
    }

    public function getCustomTag()
    {
        return $this->customTag;
    }

    public function setCustomTag($customTag)
    {
        $this->customTag = $customTag;
        $this->apiParam["custom_tag"] = $this->customTag;
    }
}
