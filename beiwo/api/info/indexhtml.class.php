<?php

/**
 * Created by PhpStorm.
 * User: 81475
 * Date: 2019/3/27
 * Time: 14:21
 */
class indexhtml {

    public $infoarr;
    public $db;
    public $api;

    public function __construct($api) {
        global $db;
        $this->db  = $db;
        $this->api = $api;
    }

    public function get_index_about_us($offset = 0, $limit = 1) {
        $res['info']  = $this->db->get('about_us', '*');
        $res['list']  = $this->db->select('about_us_category', '*', array('order' => array('indexid' => 'ASC'), 'limit' => [$offset * $limit, $limit]));
        $res['count'] = $this->db->count('about_us_category');
        return $res;
    }

    public function get_index_server() {
        $data         = $this->db->get('goods_category', '*', ['tbid' => 3]);
        $data['list'] = $this->db->select('goods_category', '*', ['category_id' => 3, 'order' => 'indexid asc']);
        return $data;
    }

    public function get_new_recommend($offset, $limit) {
        //校验分类id
        $data         = $this->db->select('goods_category', '*', array(
            'and'   => array('category_id[!]' => 0, 'is_new' => 1),
            'order' => array('indexid' => 'ASC'),
            'limit' => [$offset * $limit, $limit],
        ));
        $res['count'] = $this->db->count('goods_category', '*', array('and' => array('category_id[!]' => 0, 'is_new' => 1)));
        if ($res['count'] > 0) {
            foreach ($data as $v) {
                $x             = array();
                $x['tbid']     = $v['tbid'];
                $x['cname']    = $this->db->get('goods_category', 'name', array('tbid' => $v['category_id']));
                $x['name']     = $v['name'];
                $x['intro']    = $v['intro'];
                $x['cNameE']   = $v['english_name'];
                $x['image']    = getimg($v['image'])['0'];
                $x['xLook']    = $v['look_count'];
                $x['pid']      = $v['category_id'];
                $res['list'][] = $x;
            }
        } else {
            $res['list'] = [];
        }
        return $res;
    }

    public function get_index_pro_category() {
        $res         = $this->db->get('goods_category_info', '*');
        $res['list'] = $this->db->select('goods_category', '*', ['category_id' => 0]);
        return $res;
    }

    public function get_index_team($offset, $limit) {
        $res['list']  = $this->db->select('team', '*', ['is_index' => 1, 'order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
        $res['count'] = $this->db->count('team', ['is_index' => 1]);
        return $res;
    }

    public function get_index_certificate($offset, $limit) {
        $res['list']  = $this->db->select('about_us_certificate', '*', ['is_index' => 1, 'order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
        $res['count'] = $this->db->count('about_us_certificate', ['is_index' => 1]);
        return $res;
    }

    public function get_index_new($offset, $limit) {
        $res['list']  = $this->db->select('news', '*', ['is_index' => 1, 'order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
        $res['count'] = $this->db->count('news', ['is_index' => 1]);
        return $res;
    }

    public function get_index_partners($offset, $limit) {
        $res['list']  = $this->db->select('partners', '*', ['is_index' => 1, 'order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
        $res['count'] = $this->db->count('partners', ['is_index' => 1]);
        return $res;
    }

    public function get_index_bottom_address($offset, $limit, $connect_us_category) {
        return $this->db->select('connect_us_item', '*', ['is_index' => 1, 'category_id' => $connect_us_category, 'order' => ['indexid' => 'asc'], 'limit' => [$offset * $limit, $limit]]);
    }

    public function get_product_list($search, $offset, $limit) {
        $res['count'] = $this->db->count('goods(g)', [
            '[><]goods_sku(s)' => ['g.tbid' => 's.goods_id'],
                ], [
            'or'    => [
                'g.name[~]'           => $search,
                'g.english_name[~]'   => $search,
                'g.intro[~]'          => $search,
                's.article_number[~]' => $search
            ],
            'group' => 'g.tbid'
        ]);
        if ($res['count'] > 0) {
            $array = $this->db->select('goods(g)', [
                '[><]goods_sku(s)' => ['g.tbid' => 's.goods_id'],
                    ], [
                'g.tbid', 'g.name', 's.article_number'
                    ], [
                'or'    => [
                    'g.name[~]'           => $search,
                    'g.english_name[~]'   => $search,
                    'g.intro[~]'          => $search,
                    's.article_number[~]' => $search,
                ],
                'limit' => [$offset * $limit, $limit],
            ]);
            foreach ($array as $value) {
                $res['list'][$value['tbid']] ['tbid']        = $value['tbid'];
                $res['list'][$value['tbid']] ['name']        = $value['name'];
                $res['list'][$value['tbid']] ['item_number'] = $value['article_number'];
            }
            $res['list'] = array_values($res['list']);
        } else {
            $res['list'] = [];
        }
        return $res;
    }

}
