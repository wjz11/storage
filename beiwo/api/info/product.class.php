<?php

class product {

    public $infoarr;
    public $db;
    public $api;

    public function __construct($api) {
        global $db;
        $this->db  = $db;
        $this->api = $api;
    }

    //获取分类列表
    public function cList() {
        $data = $this->db->select('goods_category', '*', ['order' => ['indexid' => 'ASC']]);
        $res  = array();
        foreach ($data as $k => &$v) {
            $x = array();
            if ($v['category_id'] == 0) {
                $x['cId']      = $v['tbid'];
                $x['cName']    = $v['name'];
                $x['cNameE']   = $v['english_name'];
                $x['cImage']   = getimg($v['image'])['0'];
                $x['cChild']   = '';
                $res['list'][] = $x;
                unset($data[$k]);
            }
        }
        foreach ($res['list'] as &$vv) {
            foreach ($data as $vvv) {
                if ($vv['cId'] == $vvv['category_id']) {
                    $vv['cChild'] = $vv['cChild'] . ' ' . $vvv['name'];
                }
            }
        }
        return $res;
    }

    //获取分类列表(二级)
    public function cList2($cId, $offset, $limit) {
        //校验分类id
        $res['count'] = $this->db->count('goods_category', ['category_id' => $cId]);
        $this->checkId('goods_category', $cId);
        $data         = $this->db->select('goods_category', '*', [
            'and'   => ['category_id' => $cId],
            'order' => ['indexid' => 'ASC'],
            'limit' => [$offset * $limit, $limit],
        ]);
        $res['list']  = [];
        foreach ($data as $v) {
            $x                                     = array();
            $x['cId']                              = $v['tbid'];
            $x['cName']                            = $v['name'];
            $x['cNameE']                           = $v['english_name'];
            $x['cImage']                           = getimg($v['image'], 1, 0);
            $set_child['and']['goods_category_id'] = $v['tbid'];
            $x['count']                            = $this->db->count('goods', $set_child);
            if ($x['count'] > 0) {
                $set_child['order'] = ['indexid' => 'ASC'];
                $set_child['limit'] = [0, 3];
                $data_child         = $this->db->select('goods', '*', $set_child);
                foreach ($data_child as $vv) {
                    $xx           = [];
                    $xx['xId']    = $vv['tbid'];
                    $xx['xName']  = $vv['name'];
                    $xx['xNameE'] = $vv['english_name'];
                    $x['list'][]  = $xx;
                }
            } else {
                $x['list'] = [];
            }
            $res['list'][] = $x;
        }
        return $res;
    }

    //获取分类列表(二级)
    public function cList2_info($cId) {
        //校验分类id
        $this->checkId('goods_category', $cId);
        $data           = $this->db->get('goods_category', '*', array(
            'and' => array('tbid' => $cId),
        ));
        $res            = array();
        $res['cId']     = $data['tbid'];
        $res['cName']   = $data['name'];
        $res['cNameE']  = $data['english_name'];
        $res['cImage']  = getimg($data['image'])['0'];
//        单反斜杠1("<p style=\"\">"),单反斜杠2(<p style=\"\">),单反斜杠3(<p style="">),双反斜杠("<p style=\\"\\">")
        $res['content'] = $data['content'];
        return $res;
    }

    //新品推荐
    public function cList3() {
        //校验分类id
        $data = $this->db->select('goods_category', '*', array(
            'and'   => array('category_id[!]' => 0, 'is_new' => 1),
            'order' => array('indexid' => 'ASC')
        ));
        $res  = array();
        foreach ($data as $v) {
            $x             = array();
            $x['cId']      = $v['tbid'];
            $x['cName2']   = $this->db->get('goods_category', 'name', array('tbid' => $v['category_id']));
            $x['cName']    = $v['name'];
            $x['cNameE']   = $v['english_name'];
            $x['cImage']   = getimg($v['image'])['0'];
            $x['xLook']    = $v['look_count'];
            $res['list'][] = $x;
        }
        return $res;
    }

    //新品推荐（商品）
    public function cList4() {
        //校验分类id
        $data = $this->db->select('goods', '*', array(
            'and'   => array('is_new' => 1),
            'order' => array('indexid' => 'ASC')
        ));
        if ($data) {
            foreach ($data as $v) {
                $x                  = array();
                $x['good_id']       = $v['tbid'];
                $x['good_name']     = $v['name'];
                $x['category_name'] = $this->db->get('goods_category', 'name', array('tbid' => $v['goods_category_id']));
                $x['good_image']    = $v['image'] ? getimg($v['image'])[0] : '';
                $x['good_look']     = $v['look_count'] ? $v['look_count'] : 0;
                $res['list'][]      = $x;
            }
        } else {
            $res = array();
        }
        return $res;
    }

    //校验id
    public function checkId($table, $Id) {
        switch ($table) {
            case 'goods_category':
                $errorInfo = '没有此产品分类';
                $set       = array('tbid' => $Id);
                break;
            case 'goods':
                $errorInfo = '没有此产品';
                $set       = array('and' => array('tbid' => $Id, 'isdel[!]' => 1));
                break;
        }
        if (!$this->db->has($table, $set)) {
            $this->api->dataerror($errorInfo);
        }
    }

    //获取列表
    public function xList($param) {
        //校验分类id
        if ($param['cId']) {
            $this->checkId('goods_category', $param['cId']);
            $this->db->update('goods_category', array('#look_count[+]' => 1), array('tbid' => $param['cId']));
            $data          = $this->db->get('goods_category', '*', array('tbid' => $param['cId']));
            $res['cId']    = $data['tbid'];
            $res['cName']  = $data['name'];
            $res['cNameE'] = $data['english_name'];
            $res['cImage'] = getimg($data['image'])['0'];
            $res['cIntro'] = $data['intro'];
            $set['and']    = array('goods_category_id' => $param['cId']);
        } else {
            $res['cId']    = '';
            $res['cName']  = '';
            $res['cNameE'] = '';
            $res['cImage'] = '';
            $res['cIntro'] = '';
        }
        if ($param['search']) {
            $set['and']['or']['name[~]']  = $param['search'];
            $set['and']['or']['intro[~]'] = $param['search'];
            $goodsid                      = $this->db->select('goods_sku', 'goods_id', array('article_number[~]' => $param['search']));
            if (!empty($goodsid)) {
                $set['and']['or']['tbid'] = $goodsid;
            }
        }
        //查商品数据
        $res['count'] = $this->db->count('goods', $set);
        $set['order'] = array('indexid' => 'ASC', 'tbid' => 'DESC');
        $set['limit'] = array($param['offset'] * $param['limit'], $param['limit']);
        $data1        = $this->db->select('goods', '*', $set);
        foreach ($data1 as $v) {
            $x          = array();
            $x['xId']   = $v['tbid'];
            $x['xName'] = $v['name'];
//            $x['xNum']  = $v['item_number'];
            $itemcode   = $this->db->select('goods_sku', '*', array('goods_id' => $v['tbid']));
            $istr       = '';
            $ispe       = '';
            foreach ($itemcode as $key => $ival) {
                if ($key == 0) {
                    $istr = $ival['article_number'];
                    $ispe = $ival['specifications'];
                } else {
                    $num       = count(explode('-', $ival['article_number']));
                    $itemcodec = explode('-', $ival['article_number'])[$num - 1];
                    $istr      .= '/' . $itemcodec;
                    $ispe      .= '/' . $ival['specifications'];
                }
            }
            $x['xNum']     = $istr;
            $x['xSpec']    = $ispe;
            $x['xIntro']   = $v['intro'];
            $x['xImages']  = $v['image'] ? getimg($v['image']) : [];
            $res['list'][] = $x;
        }
        if ($res['count'] == 0) {
            $res['list'] = array();
        }
        return $res;
    }

    //获取单个
    public function info($param) {
        $this->checkId('goods', $param['xId']);
        $this->db->update('goods', array('#look_count[+]' => 1), array('tbid' => $param['xId']));
        $data              = $this->db->get('goods', '*', array(
            'and'   => array('tbid' => $param['xId']),
            'order' => array('indexid' => 'ASC', 'tbid' => 'DESC'),
        ));
        $res               = array();
        //产品信息
        $res['cId']        = $data['goods_category_id'];
        $res['xName']      = $data['name'];
        $res['xNameE']     = $data['english_name'];
        $res['xcName']     = $this->db->get('goods_category', 'name', ['tbid' => $data['goods_category_id']]);
        $res['xIntro']     = $data['intro'];
        $res['xDesc']      = $data['description'];
        $res['xQues']      = $data['question'];
        $res['xAtte']      = $data['attention'];
        $res['image']      = $data['image'] ? getimg($data['image']) : [];
        $res['image_sm']   = [];
        $res['time_begin'] = $data['time_begin'] ? date("Y-m-d", strtotime($data['time_begin'])) : '';
        $res['time_end']   = $data['time_end'] ? date("Y-m-d", strtotime($data['time_end'])) : '';
        $res['iscuxiao']   = $data['is_cuxiao'];
        foreach ($res['image'] as $img) {
            $sm_img = str_replace('goods/', 'goods/s_', $img);
            if (file_exists($sm_img)) {
                $res['image_sm'][] = $sm_img;
            } else {
                $res['image_sm'][] = $img;
            }
        }
        //sku信息
        $skus            = $this->db->select('goods_sku', '*', ['goods_id' => $param['xId']]);
        $res['sku_info'] = [];
        foreach ($skus as $sku) {
            $tmp                    = [];
            $tmp['goods_id']        = $sku['goods_id'];
            $tmp['article_number']  = $sku['article_number'];
            $tmp['specifications']  = $sku['specifications'];
            $tmp['list_price']      = $sku['list_price'];
            $tmp['promotion_price'] = $sku['promotion_price'];
            $res['sku_info'] []     = $tmp;
        }
        //资料下载
        $file_list = $this->db->select('mannual', '*', ['resource_id' => $param['xId'], 'resource_type' => 'goods']);
        if (count($file_list) > 0) {
            foreach ($file_list as $file) {
                $tmp                = [];
                $tmp['f_id']        = $file['tbid'];
                $tmp['file_url']    = SITEROOTURL . $file['file'];
                $tmp['file_name']   = $file['name'];
                $tmp['file_size']   = $file['size'];
                $tmp['file_count']  = $file['count'] + $file['count_virtual'];
                $res['file_list'][] = $tmp;
            }
        } else {
            $res['file_list'] = [];
        }
        return $res;
    }

    //获取分类列表(二级)
    public function cList2_all($search1, $search2, $offset1, $limit1, $offset2, $limit2) {
        //校验分类id
        $set['and']['category_id'] = [1, 2];
        if ($search1) {
            $set['and']['name[~]'] = $search1;
        }
        $res['count'] = $this->db->count('goods_category', $set);
        if ($res['count'] > 0) {
            $set['order'] = ['indexid' => 'ASC'];
            if ($limit1) {
                $set['limit'] = [$offset1 * $limit1, $limit1];
            }
            $set['and']['category_id'] = 1;
            $data1                     = $this->db->select('goods_category', '*', $set);
            $set['and']['category_id'] = 2;
            $data2                     = $this->db->select('goods_category', '*', $set);
            $data                      = array_merge($data1, $data2);
            foreach ($data as $v) {
                $x                                     = [];
                $x['cId']                              = $v['tbid'];
                $x['cName']                            = $v['name'];
                $x['cNameE']                           = $v['english_name'];
                $x['cImage']                           = getimg($v['image'], 1, 0);
                $set_child['and']['goods_category_id'] = $v['tbid'];
                if ($search2) {
                    $set_child['and']['name[~]'] = $search2;
                }
                $x['count'] = $this->db->count('goods', $set_child);
                if ($x['count'] > 0) {
                    $set_child['order'] = ['indexid' => 'ASC'];
                    if ($limit2) {
                        $set_child['limit'] = [$offset2 * $limit2, $limit2];
                    }
                    $data_child = $this->db->select('goods', '*', $set_child);
                    foreach ($data_child as $vv) {
                        $xx             = [];
                        $xx['xId']      = $vv['tbid'];
                        $xx['xName']    = $vv['name'];
                        $xx['xNameE']   = $vv['english_name'];
                        $xx['image_sm'] = getimg($vv['image'], 1, 0);
                        $x['list'][]    = $xx;
                    }
                } else {
                    $x['list'] = [];
                }
                $res['list'][] = $x;
            }
        } else {
            $res['list'] = [];
        }
        return $res;
    }

    function server_list($param) {
        if (isset($param['s_type']) && !empty($param['s_type'])) {
            if ($param['s_type'] > 0) {
                $set['and']['type'] = $param['s_type'];
            }
        }
        if (isset($param['search']) && !empty($param['search'])) {
            $set['or']['title[~]'] = $param['search'];
//            $set['or']['content[~]'] = $param['search'];
        }
        $set['and']['is_use'] = 1;
        $res['count']         = $this->db->count('server_text', $set);
        if (isset($param['limit']) && !empty($param['limit'])) {
            $set['limit'] = [$param['offset'] * $param['limit'], $param['limit']];
        }
        $set['order'] = ['indexid' => 'ASC'];
        $array        = $this->db->select('server_text', '*', $set);
        if (count($array) > 0) {
            foreach ($array as $value) {
                $tmp               = [];
                $tmp['s_id']       = $value['tbid'] ? $value['tbid'] : '';
                $tmp['title']      = $value['title'] ? $value['title'] : '';
//                $tmp['content']    = $value['content'] ? $value['content'] : '';
                $tmp['content']    = '';
                $tmp['s_type']     = $value['type'] ? $value['type'] : '';
                $tmp['createtime'] = $value['createtime'] ? $value['createtime'] : '';
                $tmp['edittime']   = $value['edittime'] ? $value['edittime'] : '';
                $tmp['url_type']   = $value['url_type'] ? $value['url_type'] : '';
                $tmp['url']        = $value['url'] ? $value['url'] : '';
                $tmp['file']       = $value['file'] ? getimg($value['file'], 1, 0) : '';
                $tmp['file_size']  = $value['size'] ? $value['size'] : '';
                $tmp['file_type']  = $value['file_type'] ? $value['file_type'] : '';
                $res['s_list'][]   = $tmp;
            }
        } else {
            $res['s_list'] = [];
        }
        return $res;
    }

    function server_info($param) {
        $set['tbid']   = $param['s_id'];
        $set['is_use'] = 1;
        $info          = $this->db->get('server_text', '*', $set);
        if ($info) {
            $res['s_id']       = $info['tbid'] ? $info['tbid'] : '';
            $res['title']      = $info['title'] ? $info['title'] : '';
//            $res['content']    = $info['content'] ? $info['content'] : '';
            $res['content']    = '';
            $res['s_type']     = $info['type'] ? $info['type'] : '';
            $res['createtime'] = $info['createtime'] ? $info['createtime'] : '';
            $res['edittime']   = $info['edittime'] ? $info['edittime'] : '';
            $res['url_type']   = $info['url_type'] ? $info['url_type'] : '';
            $res['url']        = $info['url'] ? $info['url'] : '';
            $res['file']       = $info['file'] ? getimg($info['file'], 1, 0) : '';
            $res['file_size']  = $info['size'] ? $info['size'] : '';
            $res['file_type']  = $info['file_type'] ? $info['file_type'] : '';
        } else {
            $res['s_id']       = '';
            $res['title']      = '';
            $res['content']    = '';
            $res['s_type']     = '';
            $res['createtime'] = '';
            $res['edittime']   = '';
            $res['url']        = '';
            $res['file']       = '';
            $res['file_size']  = '';
            $res['file_type']  = '';
        }
        return $res;
    }

    function add_download($table, $param) {
        return $this->db->update($table, ['#count[+]' => 1], ['tbid' => $param['d_id']]) > 0 ? 1 : 0;
    }

    function recommended_list() {
        $data_c = $this->db->select('goods_category', '*', array(
            'and'   => array('category_id[!]' => 0, 'is_new' => 1),
            'order' => array('indexid' => 'ASC')
        ));
        if (is_array($data_c) && count($data_c) > 0) {
            foreach ($data_c as $vc) {
                $x              = array();
                $x['cId']       = $vc['tbid'];
                $x['cName']     = $vc['name'];
                $x['cNameE']    = $vc['english_name'];
                $x['cImage']    = $vc['image'] ? getimg($vc['image'], 1, 0) : '';
                $x['cImage_s']  = $vc['image'] ? getimg($vc['image'], 1, 1) : '';
                $x['xLook']     = $vc['look_count'];
                $x['indexid']   = $vc['indexid'];
                $x['intro']     = $vc['intro'];
                $x['content']   = $vc['content'];
                $res['clist'][] = $x;
            }
        } else {
            $res['clist'] = [];
        }
        $data_g = $this->db->select('goods', '*', array(
            'and'   => array('is_new' => 1),
            'order' => array('indexid' => 'ASC')
        ));
        if (is_array($data_g) && count($data_g) > 0) {
            foreach ($data_g as $vg) {
                $x                  = array();
                $x['good_id']       = $vg['tbid'];
                $x['good_name']     = $vg['name'];
                $x['category_name'] = $this->db->get('goods_category', 'name', array('tbid' => $vg['goods_category_id']));
                $x['good_image']    = $vg['image'] ? getimg($vg['image'], 1, 0) : '';
                $x['good_image_s']  = $vg['image'] ? getimg($vg['image'], 1, 1) : '';
                $x['good_look']     = $vg['look_count'] ? $vg['look_count'] : 0;
                $x['indexid']       = $vg['indexid'];
                $x['intro']         = $vg['intro'];
                $x['description']   = $vg['description'];
                $res['glist'][]     = $x;
            }
        } else {
            $res['glist'] = [];
        }
        return $res;
    }

}
