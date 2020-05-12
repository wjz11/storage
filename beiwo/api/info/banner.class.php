<?php

class banner {

    public $infoarr;
    public $db;
    public $api;

    public function __construct($api) {
        global $db;
        $this->db  = $db;
        $this->api = $api;
    }

    //校验id
    public function checkId($table, $Id) {
        switch ($table) {
            case 'banner_category':
                $errorInfo = '没有此广告分类';
                $set       = array('tbid' => $Id);
                break;
            case 'banner':
                $errorInfo = '没有此广告';
                $set       = array('and' => array('tbid' => $Id, 'isdel[!]' => 1));
                break;
        }
        if (!$this->db->has($table, $set))
            $this->api->dataerror($errorInfo);
    }

    //获取分类列表
    public function cList($pid = 0) {
        $data = $this->db->select('banner_category', array('tbid', 'name'), ['pid' => $pid]);
        if (!empty($data) && is_array($data)) {
            foreach ($data as $v) {
                $x             = array();
                $x['cId']      = $v['tbid'];
                $x['cName']    = $v['name'];
                $res['list'][] = $x;
            }
        } else {
            $res['list'] = [];
        }
        return $res;
    }

    //获取列表
    public function iList($cId) {
        $data = $this->db->select('banner', '*', ['and' => ['banner_category_id' => $cId], 'order' => ['indexid' => 'ASC']]);
        if (count($data) > 0) {
            foreach ($data as $v) {
                $x                = [];
                $x['xId']         = $v['tbid'];
                $x['xName']       = $v['name'] ? $v['name'] : '';
                $x['xImage']      = $v['image'] ? getimg($v['image'], 1, 1) : '';
                $x['xImage_h5']   = $v['image_h5'] ? getimg($v['image_h5'], 1, 1) : '';
                $x['xUrl']        = $v['url'] ? $v['url'] : '';
                $x['type']        = $v['type'] ? $v['type'] : '';
                $x['resource_id'] = $v['resource_id'] ? $v['resource_id'] : '';
                $res['list'][]    = $x;
            }
        } else {
            $res['list'] = [];
        }
        return $res;
    }

    //获取单个
    public function info($xId) {
        $data               = $this->db->get('banner', '*', array('tbid' => $xId));
        $res                = array();
        $res['xId']         = $data['tbid'];
        $res['xName']       = $data['name'];
        $res['xImage']      = getimg($data['image'])[0];
        $res['xImage_h5']   = getimg($data['image_h5'])[0];
        $res['xUrl']        = $data['url'];
        $res['type']        = $data['type'];
        $res['resource_id'] = $data['resource_id'];
        return $res;
    }

}
