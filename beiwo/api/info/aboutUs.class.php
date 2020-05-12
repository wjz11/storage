<?php

class aboutUs {

    public $infoarr;
    public $db;
    public $api;

    public function __construct($api) {
        global $db;
        $this->db  = $db;
        $this->api = $api;
    }

    public function getInfoUnderstandUs() {
        $data        = $this->db->get('about_us', '*');
        $res         = array();
        $res['xInt'] = $data['intro'];
        $data2       = $this->db->select('about_us_category', '*', array('order' => array('indexid' => 'ASC')));
        $res['list'] = array();
        foreach ($data2 as $v) {
            $x             = array();
            $x['xName']    = $v['name'];
            $x['xEng']     = $v['english_name'];
            $x['xImage']   = getimg($v['image'])[0];
            $x['xContent'] = $v['content'];
            $res['list'][] = $x;
        }
        return $res;
    }

    public function get_server_list($service_type) {
        return $this->db->select('server_text', ['tbid', 'title'], ['type' => $service_type, 'order' => ['indexid' => 'asc']]);
    }

    public function get_server_info($tbid) {
        return $this->db->get('server_text', '*', ['tbid' => $tbid]);
    }

    public function get_about_us_1($offset = 0, $limit = 1) {
        $res['info']  = $this->db->get('about_us', '*');
        $res['list']  = $this->db->select('about_us_category', '*', array('order' => array('indexid' => 'ASC'), 'limit' => [$offset * $limit, $limit]));
        $res['count'] = $this->db->count('about_us_category');
        return $res;
    }

    public function get_about_us_team($type) {
        $res['list']  = $this->db->select('team', '*', ['type' => $type]);
        $res['count'] = $this->db->count('team', ['type' => $type]);
        return $res;
    }

    public function get_about_us_team_info($tbid) {
        $this_info        = $this->db->get('team', '*', ['tbid' => $tbid]);
        $res['this_info'] = $this_info;
        $res['prov_id']   = $this->db->get('course', 'tbid', ['tbid[<]' => $this_info['tbid'], 'order' => ['tbid' => 'DESC']]);
        $res['next_id']   = $this->db->get('course', 'tbid', ['tbid[>]' => $this_info['tbid'], 'order' => ['tbid' => 'ASC']]);
        return $res;
    }

    public function get_about_us_course_time() {
        $q = $this->db->query("SELECT `tbid`,`show_time` FROM tb_course GROUP BY `show_time`");
        $q->execute();
        return $q->fetchAll();
    }

    public function get_about_us_cource_info($tbid) {
        $this_info        = $this->db->get('course', '*', ['tbid' => $tbid]);
        $res['this_info'] = $this_info;
        $res['prov_id']   = $this->db->get('course', 'tbid', ['tbid[<]' => $this_info['tbid'], 'order' => ['tbid' => 'DESC']]);
        $res['next_id']   = $this->db->get('course', 'tbid', ['tbid[>]' => $this_info['tbid'], 'order' => ['tbid' => 'ASC']]);
        return $res;
    }

    public function get_about_us_course_list() {
        return $this->db->select('course', '*', ['order' => ['show_time' => 'asc']]);
    }

    public function get_about_us_certificate($offset, $limit) {
        $res['info']  = $this->db->get('about_us', '*');
        $res['list']  = $this->db->select('about_us_certificate', '*', ['order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
        $res['count'] = $this->db->count('about_us_certificate');
        return $res;
    }

    public function get_about_us_certificate_banner($offset, $limit) {
        $res['list']  = $this->db->select('about_us_certificate', '*', ['order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
        $res['count'] = $this->db->count('about_us_certificate');
        return $res;
    }

    public function getInfoContentUs() {
        //总部信息
        $data1         = $this->db->get('connect_us', '*');
        $res           = array();
        $res['xTitle'] = $data1['name2'];
        $res['xServ']  = $data1['hotline'];
        $res['xName']  = $data1['name'];
        $res['xAddr']  = $data1['address'];
        $res['xTelA']  = $data1['switchboard'];
        $res['xTel']   = $data1['phone'];
        $res['xFax']   = $data1['fox'];
        $res['xEma']   = $data1['email'];
        //其他信息
        $data2         = $this->db->select('connect_us_category', array(
            'tbid', 'name', 'english_name'
                ), array(
            'order' => array('indexid' => 'ASC')
        ));
        $res['list']   = array();
        foreach ($data2 as $v) {
            $x          = array();
            $x['xName'] = $v['name'];
            $x['xEng']  = $v['english_name'];
            $data3      = $this->db->select('connect_us_item', '*', array(
                'and'   => array('category_id' => $v['tbid']),
                'order' => array('indexid' => 'ASC')
            ));
            $x['list']  = array();
            foreach ($data3 as $vv) {
                $xx          = array();
                $xx['xName'] = $vv['name'] ? $vv['name'] : '';
                $xx['xAddr'] = $vv['address'];
                $xx['xTel']  = $vv['phone'];
                $xx['xTelA'] = $vv['switchboard'];
                $xx['xFax']  = $vv['fox'];
                $xx['xEma']  = $vv['email'];
                $x['list'][] = $xx;
            }
            $res['list'][] = $x;
        }
        return $res;
    }

    public function getInfoContentUs1($category_id) {
        $data2             = $this->db->get('connect_us_category', array(
            'name', 'english_name'
                ), array(
            'and'   => array('tbid' => $category_id),
            'order' => array('indexid' => 'ASC')
        ));
        $res['array_info'] = array();
        $x                 = array();
        $x['xName']        = $data2['name'];
        $x['xEng']         = $data2['english_name'];
        $data3             = $this->db->select('connect_us_item', '*', array(
            'and'   => array('category_id' => $category_id),
            'order' => array('indexid' => 'ASC')
        ));
        $x['arr_info']     = array();
        foreach ($data3 as $vv) {
            $xx              = array();
            $xx['xName']     = $vv['name'] ? $vv['name'] : '';
            $xx['xProvince'] = $vv['city'] ? $vv['city'] : '';
            $xx['xAddr']     = $vv['address'] ? $vv['address'] : '';
            $xx['xTel']      = $vv['phone'] ? $vv['phone'] : '';
            $xx['xTelA']     = $vv['switchboard'] ? $vv['switchboard'] : '';
            $xx['xFax']      = $vv['fox'] ? $vv['fox'] : '';
            $xx['xEma']      = $vv['email'] ? $vv['email'] : '';
            $xx['xAddrLng']  = $vv['lng'] ? $vv['lng'] : '';
            $xx['xAddrLat']  = $vv['lat'] ? $vv['lat'] : '';
            $x['arr_info'][] = $xx;
        }
        $res = $x;
        return $res;
    }

}
