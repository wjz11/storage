<?php

$this->subset_api = array('name' => '首页接口');
if (isset($this->module)) {
    $that                                  = $this->module;
    //配置公用参数
    $this->infoarr['name']                 = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['image']                = array('type' => 'string', 'summary' => '图片');
    $this->infoarr['intro']                = array('type' => 'string', 'summary' => '简介');
    $this->infoarr['set_up_time']          = array('type' => 'int', 'summary' => '成立时间');
    $this->infoarr['pro_count']            = array('type' => 'int', 'summary' => '产品种类');
    $this->infoarr['provinces_count']      = array('type' => 'int', 'summary' => '销售省份');
    $this->infoarr['year_count']           = array('type' => 'int', 'summary' => '年销售额');
    $this->infoarr['patent_for_invention'] = array('type' => 'int', 'summary' => '发明专利数');
    $this->infoarr['new_utility_patents']  = array('type' => 'int', 'summary' => '实用新型专利');
    $this->infoarr['national_standard']    = array('type' => 'int', 'summary' => '国家标注');
    $this->infoarr['Industry_technology']  = array('type' => 'int', 'summary' => '行业技术');
    $this->infoarr['name_en']              = array('type' => 'string', 'summary' => '英文名');
    $this->infoarr['name_deputy']          = array('type' => 'string', 'summary' => '副名称');
    $this->infoarr['list']                 = array('type' => 'list ', 'summary' => '数组数据');
    $this->infoarr['cid']                  = array('type' => 'int ', 'summary' => '分类id');
    $this->infoarr['logo']                 = array('type' => 'string ', 'summary' => 'logo');
    $this->infoarr['icon1']                = array('type' => 'string ', 'summary' => '首页服务图标默认');
    $this->infoarr['icon2']                = array('type' => 'string ', 'summary' => '首页服务图标选中');
    $this->infoarr['offset']               = array('type' => 'int ', 'summary' => '起始条数');
    $this->infoarr['limit']                = array('type' => 'int ', 'summary' => '每页条数');
    $this->infoarr['tbid']                 = array('type' => 'int ', 'summary' => '新品id');
    $this->infoarr['cname']                = array('type' => 'string ', 'summary' => '分类名称');
    $this->infoarr['title']                = array('type' => 'string ', 'summary' => '头衔');
    $this->infoarr['createtime']           = array('type' => 'string ', 'summary' => '新闻日期');
    $this->infoarr['name#1']               = array('type' => 'string', 'summary' => '标题');
    $this->infoarr['url']                  = array('type' => 'string', 'summary' => '外链');
    $this->infoarr['city']                 = array('type' => 'string', 'summary' => '城市');
    $this->infoarr['address']              = array('type' => 'string', 'summary' => '地址');
    $this->infoarr['phone']                = array('type' => 'string', 'summary' => '电话');
    $this->infoarr['fox']                  = array('type' => 'string', 'summary' => '传真');
    $this->infoarr['email']                = array('type' => 'string', 'summary' => '邮箱');
    $this->infoarr['switchboard']          = array('type' => 'string', 'summary' => '总机');
    $this->infoarr['search']               = array('type' => 'string', 'summary' => '产品名或产品编号');
    $this->infoarr['tbid#1']               = array('type' => 'int ', 'summary' => '产品id');
    $this->infoarr['name#2']               = array('type' => 'string', 'summary' => '产品名');
    $this->infoarr['item_number']          = array('type' => 'string', 'summary' => '产品编号');
    $this->infoarr['count']                = array('type' => 'int', 'summary' => '统计');
    $this->infoarr['english_name']         = array('type' => 'string', 'summary' => '英文名');
    $this->infoarr['content']              = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['connect_us_category']  = array('type' => 'string', 'summary' => '类型', 'list' => [1 => '公司直属', 4 => '全国各省市代理商']);
    $this->infoarr['addr_lng']             = array('type' => 'string', 'summary' => '经度');
    $this->infoarr['addr_lat']             = array('type' => 'string', 'summary' => '纬度');
    $this->infoarr['cNameE']               = array('type' => 'string', 'summary' => '英文名');
    $this->infoarr['pid']                  = array('type' => 'int', 'summary' => '父ID（同顶级分类）');
}


$this->info            = array('req' => 'search');
$this->info['summary'] = '搜索接口（搜索产品名和产品编号）';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('search', 'offset', 'limit');
    //输出参数
    $this->fields         = array('list' => ['tbid', 'name', 'item_number'], 'count');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->get_product_list($param['search'], $param['offset'], $param['limit']);
//    $data['list']         = [];
//    foreach ($res['list'] as $v) {
//        $x                = [];
//        $x['tbid']        = $v['tbid'];
//        $x['name']        = $v['name'];
//        $x['item_number'] = $v['item_number'];
//        $data['list'][]   = $x;
//    }
//    $data['count'] = $res['count'] ? $res['count'] : '';
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'index.bottom_address');
$this->info['summary'] = '底部地址';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit', 'connect_us_category');
    //输出参数
    $this->fields         = ['list' => ['name', 'city', 'address', 'phone', 'fox', 'email', 'switchboard', 'addr_lng', 'addr_lat']];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_bottom_address($param['offset'], $param['limit'], $param['connect_us_category']);
    $data['list']         = [];
    if ($res) {
        foreach ($res as $v) {
            $x                = [];
            $x['name']        = $v['name'] ? $v['name'] : '';
            $x['city']        = $v['city'] ? $v['city'] : '';
            $x['address']     = $v['address'] ? $v['address'] : '';
            $x['phone']       = $v['phone'] ? $v['phone'] : '';
            $x['fox']         = $v['fox'] ? $v['fox'] : '';
            $x['email']       = $v['email'] ? $v['email'] : '';
            $x['switchboard'] = $v['switchboard'] ? $v['switchboard'] : '';
            $x['addr_lng']    = $v['lng'] ? $v['lng'] : '';
            $x['addr_lat']    = $v['lat'] ? $v['lat'] : '';
            $data['list'][]   = $x;
        }
    }
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.partners');
$this->info['summary'] = '合作伙伴';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit');
    //输出参数
    $this->fields         = ['list' => ['tbid', 'image', 'url'], 'count'];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_partners($param['offset'], $param['limit']);
    $data['list']         = [];
    if ($res['list']) {
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['tbid']      = $v['tbid'] ? $v['tbid'] : '';
            $x['image']     = $v['image'] ? getimg($v['image'])[0] : '';
            $x['url']       = $v['url'] ? $v['url'] : '';
            $data['list'][] = $x;
        }
    }
    $data['count'] = $res['count'];
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.new');
$this->info['summary'] = '新闻中心';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit');
    //输出参数
    $this->fields         = ['list' => ['tbid', 'image', 'createtime', 'name#1', 'intro'], 'count'];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_new($param['offset'], $param['limit']);
    $data['list']         = [];
    if ($res['list']) {
        foreach ($res['list'] as $v) {
            $x               = [];
            $x['tbid']       = $v['tbid'] ? $v['tbid'] : '';
            $x['image']      = $v['image'] ? getimg($v['image'])[0] : '';
            $x['createtime'] = $v['createtime'] ? date('Y-m-d', strtotime($v['createtime'])) : '';
            $x['name']       = $v['name'] ? $v['name'] : '';
            $x['intro']      = $v['intro'] ? $v['intro'] : '';
            $data['list'][]  = $x;
        }
    }
    $data['count'] = $res['count'];
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.certificate');
$this->info['summary'] = '倍沃成就';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit');
    //输出参数
    $this->fields         = ['list' => ['tbid', 'image'], 'count'];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_certificate($param['offset'], $param['limit']);
    $data['list']         = [];
    if ($res['list']) {
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['tbid']      = $v['tbid'] ? $v['tbid'] : '';
            $x['image']     = $v['image'] ? getimg($v['image'])[0] : '';
            $data['list'][] = $x;
        }
    }
    $data['count'] = $res['count'];
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.team');
$this->info['summary'] = '团队介绍';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit');
    //输出参数
    $this->fields         = ['list' => ['tbid', 'image', 'name', 'title', 'intro'], 'count'];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_team($param['offset'], $param['limit']);
    $data['list']         = [];
    if ($res['list']) {
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['tbid']      = $v['tbid'] ? $v['tbid'] : '';
            $x['image']     = $v['image'] ? getimg($v['image'])[0] : '';
            $x['name']      = $v['name'] ? $v['name'] : '';
            $x['title']     = $v['title'] ? $v['title'] : '';
            $x['intro']     = $v['intro'] ? $v['intro'] : '';
            $data['list'][] = $x;
        }
    }
    $data['count'] = $res['count'];
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.pro_category');
$this->info['summary'] = '产品分类';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = [
        'name_en', 'name', 'name_deputy', 'intro', 'image', 'list' => [
            'cid', 'name', 'intro', 'image'
        ]
    ];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_pro_category();
    $data                 = [];
    if ($res) {
        $data['name_en']     = $res['name_en'] ? $res['name_en'] : '';
        $data['name']        = $res['name'] ? $res['name'] : '';
        $data['name_deputy'] = $res['name_deputy'] ? $res['name_deputy'] : '';
        $data['intro']       = $res['intro'] ? $res['intro'] : '';
        $data['image']       = $res['image'] ? getimg($res['image'])[0] : '';
        $data['list']        = [];
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['cid']       = $v['tbid'];
            $x['name']      = $v['name'];
            $x['intro']     = $v['intro'];
            $x['image']     = $v['image'] ? getimg($v['image'])[0] : '';
            $data['list'][] = $x;
        }
    }
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.new_recommend');
$this->info['summary'] = '新品推荐';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit');
    //输出参数
    $this->fields         = ['list' => ['tbid', 'cname', 'name', 'intro', 'image', 'cNameE', 'pid'], 'count'];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->get_new_recommend($param['offset'], $param['limit']);
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.server');
$this->info['summary'] = '倍沃服务';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = ['name_en', 'name', 'name_deputy', 'intro', 'list' => [
            'cid', 'logo', 'name', 'intro', 'icon1', 'icon2'
    ]];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_index_server();
    $data                 = [];
    if ($res) {
        $data['name_en']     = $res['english_name'] ? $res['english_name'] : '';
        $data['name']        = $res['name'] ? $res['name'] : '';
        $data['name_deputy'] = $res['name_deputy'] ? $res['name_deputy'] : '';
        $data['intro']       = $res['intro'] ? $res['intro'] : '';
        $data['list']        = [];
        if ($res['list']) {
            foreach ($res['list'] as $v) {
                $x              = [];
                $x['cid']       = $v['tbid'];
                $x['logo']      = getimg($v['logo'])[0];
                $x['icon1']     = getimg($v['image_icon_1'])[0];
                $x['icon2']     = getimg($v['image_icon_2'])[0];
                $x['name']      = $v['name'];
                $x['intro']     = $v['intro'];
                $data['list'][] = $x;
            }
        }
    }
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'index.about_us');
$this->info['summary'] = '关于我们';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array(['offset', 0], ['limit', 0]);
    //输出参数
    $this->fields         = ['list' => ['image', 'name', 'english_name', 'content'],
        'count', 'set_up_time', 'pro_count', 'provinces_count', 'year_count', 'patent_for_invention', 'new_utility_patents', 'national_standard', 'Industry_technology'
    ];
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    if (isset($param['offset']) && !empty($param['offset']) && isset($param['limit']) && !empty($param['limit'])) {
        $res = $that->get_index_about_us($param['offset'], $param['limit']);
    } else {
        $res = $that->get_index_about_us();
    }
    $list         = $res['list'];
    $data['list'] = [];
    if ($list) {
        foreach ($list as $v) {
            $x                 = [];
            $x['image']        = $v['image'] ? getimg($v['image'])[0] : '';
            $x['name']         = $v['name'] ? $v['name'] : '';
            $x['english_name'] = $v['english_name'] ? $v['english_name'] : '';
            $x['content']      = $v['content'] ? $v['content'] : '';
            $data['list'][]    = $x;
        }
        $data['count'] = $list ? $res['count'] : 0;
    }
    $info                         = $res['info'];
    $data['set_up_time']          = $info['set_up_time'] ? $info['set_up_time'] : '';
    $data['pro_count']            = $info['pro_count'] ? $info['pro_count'] : '';
    $data['provinces_count']      = $info['provinces_count'] ? $info['provinces_count'] : '';
    $data['year_count']           = $info['year_count'] ? $info['year_count'] : '';
    $data['patent_for_invention'] = $info['patent_for_invention'] ? $info['patent_for_invention'] : '';
    $data['new_utility_patents']  = $info['new_utility_patents'] ? $info['new_utility_patents'] : '';
    $data['national_standard']    = $info['national_standard'] ? $info['national_standard'] : '';
    $data['Industry_technology']  = $info['Industry_technology'] ? $info['Industry_technology'] : '';
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();
