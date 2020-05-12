<?php

class news
{

    public $infoarr;
    public $db;
    public $api;

    public function __construct($api)
    {
        global $db;
        $this->db = $db;
        $this->api = $api;
    }

    //获取分类列表
    public function cList()
    {
        $data = $this->db->select('news_category', array('tbid', 'name'));
        $res = array();
        foreach ($data as $v) {
            $x = array();
            $x['cId'] = $v['tbid'];
            $x['cName'] = $v['name'];
            $res['list'][] = $x;
        }
        return $res;
    }

    //校验id
    public function checkId($table, $Id)
    {
        switch ($table) {
            case 'news_category':
                $errorInfo = '没有此新闻分类';
                $set = array('tbid' => $Id);
                break;
            case 'news':
                $errorInfo = '没有此新闻';
                $set = array('and' => array('tbid' => $Id, 'isdel[!]' => 1));
                break;
        }
        if (!$this->db->has($table, $set)) {
            $this->api->dataerror($errorInfo);
        }
    }

    //获取列表
    public function iList($cId, $offset, $limit, $isBanner)
    {
        $set = array();
        if ($cId > 0) {
            $set['and'][] = array('n.news_category_id' => $cId);
        }
        if ($isBanner > 0) {
            $set['and'][] = array('n.is_banner' => 1);
        }
        $res = array();
        $res['count'] = $this->db->count('news(n)', $set);
        $set['order'] = array('is_top' => 'DESC', 'indexid' => 'ASC', 'createtime' => 'DESC');
        $set['limit'] = array($offset * $limit, $limit);
        $data = $this->db->select('news(n)',
            array('[><]news_category(nc)' => array('news_category_id' => 'tbid')),
            array('n.*', 'nc.name as cname'), $set);
        foreach ($data as $v) {
            $x = array();
            $x['xId'] = $v['tbid'];
            $x['xImg'] = getimg($v['image'])[0];
            $x['xName'] = $v['name'];
            $x['cName'] = $v['cname'];
            $x['xIntro'] = $v['intro'];
            $x['xLook'] = $v['look_count'];
            $x['xShowTime'] = date('Y-m-d', strtotime($v['createtime']));
            $x['xBeg'] = $v['news_category_id'] == 2 ? date('Y-m-d', strtotime($v['begintime'])) : '';
            $x['xEnd'] = $v['news_category_id'] == 2 ? date('Y-m-d', strtotime($v['endtime'])) : '';
            $x['xTop'] = $v['is_top'];
            $x['xHot'] = $v['is_hot'];
            $res['list'][] = $x;
        }
        if ($res['count'] == 0) {
            $res['list'] = array();
        }
        return $res;
    }

    //获取单个
    public function info($xId)
    {
        //增加浏览量
        $this->db->update('news', array('#look_count[+]' => 1), array('tbid' => $xId));
        //获取信息
        $data = $this->db->get('news', '*', array('tbid' => $xId));
        $res = array();
        //获取详情
        $res['xName'] = $data['name'];
        $res['xShowTime'] = date('Y-m-d', strtotime($data['createtime']));
        $res['xSource'] = $data['source'];
        $res['xAuthor'] = $data['author'];
        $res['xIntro'] = $data['intro'];
        $res['xContent'] = $data['content'];
        $res['xLook'] = $data['look_count'];
        $res['xBeg'] = $data['news_category_id'] == 2 ? date('Y-m-d', strtotime($data['begintime'])) : '';
        $res['xEnd'] = $data['news_category_id'] == 2 ? date('Y-m-d', strtotime($data['endtime'])) : '';
        //获取上一个
        $previousInfo = $this->db->get('news', array('tbid', 'name', 'createtime'), array(
            'and' => array('tbid[<]' => $data['tbid'], 'news_category_id' => $data['news_category_id'], 'isdel[!]' => 1),
            'order' => array('tbid' => 'DESC')));
        $res['previousId'] = $previousInfo['tbid'] ? $previousInfo['tbid'] : 0;
        $res['previousName'] = $previousInfo['name'] ? $previousInfo['name'] : '';
        $res['previousTime'] = $previousInfo['createtime'] ? date('m-d', strtotime($previousInfo['createtime'])) : '';
        //获取下一个
        $nextInfo = $this->db->get('news', array('tbid', 'name', 'createtime'), array(
            'and' => array('tbid[>]' => $data['tbid'], 'news_category_id' => $data['news_category_id'], 'isdel[!]' => 1),
            'order' => array('tbid' => 'ASC')));
        $res['nextId'] = $nextInfo['tbid'] ? $nextInfo['tbid'] : 0;
        $res['nextName'] = $nextInfo['name'] ? $nextInfo['name'] : '';
        $res['nextTime'] = $nextInfo['createtime'] ? date('m-d', strtotime($nextInfo['createtime'])) : '';
        return $res;
    }

}
