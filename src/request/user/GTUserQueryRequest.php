<?php
namespace tinymeng\getui\request\user;

use tinymeng\getui\request\GTApiRequest;

class GTUserQueryRequest extends GTApiRequest
{
    private $tag = array();

    public function getTag()
    {
        return $this->tag;
    }

    public function addTag($condition)
    {
        array_push($this->tag, $condition);
    }

    public function setTag($conditions)
    {
        $this->tag = $conditions;
    }

    public function getApiParam()
    {
        $this->apiParam["tag"] = array();
        foreach ($this->tag as $value) {
            array_push($this->apiParam["tag"], $value->getApiParam());
        }
        return $this->apiParam;
    }
}
