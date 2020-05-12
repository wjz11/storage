<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of helpCenter
 *
 * @author acx
 */
class helpCenter {

    //put your code here
    public $infoarr;
    public $db;
    public $api;

    public function __construct($api) {
        global $db;
        $this->db  = $db;
        $this->api = $api;
    }

    public function xList($param) {
        $where = array();
        if (isset($param['search'])) {
            $where['and'][] = array('name[~]' => $param['search']);
        }
        if (isset($param['cId'])) {
            $where['and'][] = array('category_id' => $param['cId']);
        }
        if (isset($param['offset']) && isset($param['limit'])) {
            $where['limit'] = array($param['offset'] * $param['limit'], $param['limit']);
        }
        $res['count'] = $this->db->count('mannual', $where);
        $data         = $this->db->select('mannual', '*', $where);
        $res['list']  = array();
        foreach ($data as $v) {
            $x             = array();
            $x['xName']    = $v['name'];
            $x['xSize']    = $v['size'];
            $x['xDown']    = $v['count'] . '次';
            $x['xUrl']     = getimg($v['file'])['0'];
            $x['xType']    = $v['file_type'];
            $res['list'][] = $x;
        }

        return $res;
    }

    public function problemList($param) {
        $where = array();
        if (isset($param['search'])) {
            $where['or'] = array('problem[~]' => $param['search'], 'answer[~]' => $param['search']);
        }
        if (isset($param['offset']) && isset($param['limit'])) {
            $where['limit'] = array($param['offset'] * $param['limit'], $param['limit']);
        }
        $where['order'] = ['indexid', 'ASC'];
        $data           = $this->db->select('common_problem', '*', $where);
        $res['list']    = array();
        foreach ($data as $v) {
            $x              = array();
            $x['xQuestion'] = $v['problem'];
            $x['xAnswer']   = $v['answer'];
            $res['list'][]  = $x;
        }
        return $res;
    }

    public function cList() {
        $data        = $this->db->select('precaution_category', '*');
        $res['list'] = array();
        foreach ($data as $v) {
            $x             = array();
            $x['cId']      = $v['tbid'];
            $x['cName']    = $v['name'];
            $x['cEng']     = $v['english_name'];
            $x['cUrl']     = getimg($v['image'])['0'];
            $res['list'][] = $x;
        }
        return $res;
    }

    public function cList2() {
        $data        = $this->db->select('mannual_category', '*');
        $res['list'] = array();
        foreach ($data as $v) {
            $x             = array();
            $x['cId']      = $v['tbid'];
            $x['cName']    = $v['name'];
            $x['cEng']     = $v['english_name'];
            $x['cLogo']    = getimg($v['logo'])['0'];
            $x['cImg']     = getimg($v['image'])['0'];
            $res['list'][] = $x;
        }
        return $res;
    }

    public function xInfoList($param) {
        $data        = $this->db->select('precaution', '*',
                                         array(
                    'and'   => array('category_id' => $param['cId']),
                    'order' => array('indexid' => 'ASC'),
                )
        );
        $res['list'] = array();
        foreach ($data as $v) {
            $x             = array();
            $x['xOrderId'] = $v['indexid'];
            $x['xContent'] = $v['precaution'];
            $res['list'][] = $x;
        }
        return $res;
    }

}
