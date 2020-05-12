<?php

$this->subset_api = array('name' => '关于我们');
if (isset($this->module)) {
    $that                                  = $this->module;
    $this->infoarr['xInt']                 = array('type' => 'string', 'summary' => '简介');
    $this->infoarr['xCul']                 = array('type' => 'string', 'summary' => '企业文化');
    $this->infoarr['xServ']                = array('type' => 'string', 'summary' => '客服热线');
    $this->infoarr['xName']                = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['xAddr']                = array('type' => 'string', 'summary' => '地址');
    $this->infoarr['xTelA']                = array('type' => 'string', 'summary' => '总计');
    $this->infoarr['xTel']                 = array('type' => 'string', 'summary' => '电话');
    $this->infoarr['xFax']                 = array('type' => 'string', 'summary' => '传真');
    $this->infoarr['xEma']                 = array('type' => 'string', 'summary' => '邮箱');
    $this->infoarr['list']                 = array('type' => 'array', 'summary' => '列表数据');
    $this->infoarr['array_info']           = array('type' => 'array', 'summary' => '列表数据');
    $this->infoarr['arr_info']             = array('type' => 'array', 'summary' => '内部数组列表数据');
    $this->infoarr['xEng']                 = array('type' => 'string', 'summary' => '名称(英文)');
    $this->infoarr['xImage']               = array('type' => 'string', 'summary' => '图片');
    $this->infoarr['xContent']             = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['xTitle']               = array('type' => 'string', 'summary' => '热线上面的文字');
    $this->infoarr['tbid']                 = array('type' => 'int', 'summary' => '服务id');
    $this->infoarr['title']                = array('type' => 'string', 'summary' => '服务标题');
    $this->infoarr['content']              = array('type' => 'string', 'summary' => '服务内容');
    $this->infoarr['count']                = array('type' => 'int', 'summary' => '统计');
    $this->infoarr['brand_intro']          = array('type' => 'string', 'summary' => '品牌介绍');
    $this->infoarr['image']                = array('type' => 'string', 'summary' => '图片');
    $this->infoarr['intro']                = array('type' => 'string', 'summary' => '简介');
    $this->infoarr['set_up_time']          = array('type' => 'int', 'summary' => '成立时间');
    $this->infoarr['pro_count']            = array('type' => 'int', 'summary' => '产品种类');
    $this->infoarr['provinces_count']      = array('type' => 'int', 'summary' => '销售省份');
    $this->infoarr['year_count']           = array('type' => 'int', 'summary' => '年销售额');
    $this->infoarr['content#1']            = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['offset']               = array('type' => 'int', 'summary' => '起始条数');
    $this->infoarr['limit']                = array('type' => 'int', 'summary' => '每页数量');
    $this->infoarr['tbid#1']               = array('type' => 'int', 'summary' => '团队id');
    $this->infoarr['name']                 = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['title#1']              = array('type' => 'string', 'summary' => '头衔');
    $this->infoarr['type']                 = array('type' => 'int', 'summary' => '团队类型', 'list' => [1 => '研发团队', 2 => '管理团队']);
    $this->infoarr['tbid#2']               = array('type' => 'int', 'summary' => '时间轴id');
    $this->infoarr['show_time']            = array('type' => 'string', 'summary' => '时间轴展示时间');
    $this->infoarr['prov_id']              = array('type' => 'int', 'summary' => '上一个历程的id');
    $this->infoarr['next_id']              = array('type' => 'int', 'summary' => '下一个历程的id');
    $this->infoarr['patent_for_invention'] = array('type' => 'int', 'summary' => '发明专利');
    $this->infoarr['new_utility_patents']  = array('type' => 'int', 'summary' => '实用新型专利');
    $this->infoarr['national_standard']    = array('type' => 'int', 'summary' => '国家标准');
    $this->infoarr['Industry_technology']  = array('type' => 'int', 'summary' => '行业技术');
    $this->infoarr['tbid#3']               = array('type' => 'int', 'summary' => '成就id');
    $this->infoarr['time']                 = array('type' => 'string', 'summary' => '时间');
    $this->infoarr['english_name']         = array('type' => 'string', 'summary' => '英文名');
    $this->infoarr['image#1']              = array('type' => 'string', 'summary' => '头像');
    $this->infoarr['prov_id#1']            = array('type' => 'int', 'summary' => '上一个团队成员id');
    $this->infoarr['next_id#1']            = array('type' => 'int', 'summary' => '下一个团队成员id');
    $this->infoarr['category_id']          = array('type' => 'int', 'summary' => '销售网络分类id', 'list' => [1 => '其他分部', 4 => '代理商名录']);
    $this->infoarr['xProvince']            = array('type' => 'string', 'summary' => '省份');
    $this->infoarr['lng']                  = array('type' => 'string', 'summary' => '经度');
    $this->infoarr['lat']                  = array('type' => 'string', 'summary' => '纬度');
    $this->infoarr['course']               = array('type' => 'string', 'summary' => '历程图');
    $this->infoarr['service_type']         = array('type' => 'int', 'summary' => '服务类型', 'list' => [1 => '合同服务', 2 => '技术指南']);
    $this->infoarr['xAddrLng']             = array('type' => 'string', 'summary' => '地址经度');
    $this->infoarr['xAddrLat']             = array('type' => 'string', 'summary' => '地址纬度');
}

$this->info            = array('req' => 'about_us.certificate_banner');
$this->info['summary'] = '关于我们-倍沃成就轮播';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('offset', 'limit');
    //输出参数
    $this->fields         = array('list' => ['image'], 'count');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_about_us_certificate_banner($param['offset'], $param['limit']);
    $data['list']         = [];
    if ($res['list']) {
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['image']     = $v['image'] ? getimg($v['image']) : '';
            $data['list'][] = $x;
        }
    }
    $data['count'] = $res['count'] ? $res['count'] : '';
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();

$this->info            = array('req' => 'about_us.certificate');
$this->info['summary'] = '关于我们-倍沃成就';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method']         = 'GET';
    //输入参数
    $this->parameter              = array('offset', 'limit');
    //输出参数
    $this->fields                 = array('intro', 'patent_for_invention', 'new_utility_patents', 'national_standard', 'Industry_technology', 'list' => [
            'tbid', 'time', 'name'
        ], 'count');
    //初始化接口参数，返回输入参数
    $param                        = $this->apiinit();
    //获取活动列表
    $res                          = $that->get_about_us_certificate($param['offset'], $param['limit']);
    $data['intro']                = $res['intro'] ? $res['intro'] : '';
    $data['patent_for_invention'] = $res['patent_for_invention'] ? $res['patent_for_invention'] : '';
    $data['new_utility_patents']  = $res['new_utility_patents'] ? $res['new_utility_patents'] : '';
    $data['national_standard']    = $res['national_standard'] ? $res['national_standard'] : '';
    $data['Industry_technology']  = $res['Industry_technology'] ? $res['Industry_technology'] : '';
    $data['list']                 = [];
    if ($res['list']) {
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['tbid']      = $v['tbid'] ? $v['tbid'] : '';
            $x['time']      = $v['time'] ? $v['time'] : '';
            $x['name']      = $v['name'] ? $v['name'] : '';
            $data['list'][] = $x;
        }
    }
    $data['count'] = $res['count'] ? $res['count'] : '';
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'about_us.course.info');
$this->info['summary'] = '关于我们-倍沃历程详情';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('tbid#2');
    //输出参数
    $this->fields         = array('prov_id', 'image', 'intro', 'show_time', 'content#1', 'next_id');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取列表
    $res                  = $that->get_about_us_cource_info($param['tbid']);
    $data                 = [];
    if ($res) {
        $data['image']     = $res['this_info']['image'] ? getimg($res['this_info']['image']) : '';
        $data['intro']     = $res['this_info']['intro'] ? $res['this_info']['intro'] : '';
        $data['show_time'] = $res['this_info']['show_time'] ? date('Y-m', $res['this_info']['show_time']) : '';
        $data['content']   = $res['this_info']['content'] ? $res['this_info']['content'] : '';
        $data['prov_id']   = $res['prov_id'] ? $res['prov_id'] : '';
        $data['next_id']   = $res['next_id'] ? $res['next_id'] : '';
    }
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'about_us.course.time');
$this->info['summary'] = '关于我们-倍沃历程时间轴';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('list' => ['tbid#2', 'show_time']);
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_about_us_course_time($param['type']);
    $data                 = [];
    if ($res) {
        foreach ($res as $v) {
            $x              = [];
            $x['tbid']      = $v['tbid'] ? $v['tbid'] : '';
            $x['show_time'] = $v['show_time'] ? date('Y-m', $v['show_time']) : '';
            $data['list'][] = $x;
        }
    }
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'about_us.course.list');
$this->info['summary'] = '关于我们-倍沃历程';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('list' => ['tbid#2', 'show_time', 'image', 'intro', 'show_time', 'content#1'], 'course');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_about_us_course_list();
    $data                 = [];
    if ($res) {
        foreach ($res as $v) {
            $x              = [];
            $x['tbid']      = $v['intro'] ? $v['tbid'] : '';
            $x['image']     = $v['image'] ? getimg($v['image'])[0] : '';
            $x['intro']     = $v['intro'] ? $v['intro'] : '';
            $x['show_time'] = $v['show_time'] ? date('Y-m', $v['show_time']) : '';
            $x['content']   = $v['content'] ? $v['content'] : '';
            $data['list'][] = $x;
        }
        $course         = $this->db->get('about_us', 'course');
        $data['course'] = $course ? getimg($course)[0] : '';
    }
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'about_us.team.info');
$this->info['summary'] = '关于我们-倍沃团队详情';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('tbid#1');
    //输出参数
    $this->fields         = array('tbid#1', 'name', 'title#1', 'intro', 'image#1', 'content#1', 'prov_id#1', 'next_id#1');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_about_us_team_info($param['tbid']);
    $data['tbid']         = $res['this_info']['tbid'] ? $res['this_info']['tbid'] : '';
    $data['name']         = $res['this_info']['name'] ? $res['this_info']['name'] : '';
    $data['title']        = $res['this_info']['title'] ? $res['this_info']['title'] : '';
    $data['intro']        = $res['this_info']['intro'] ? $res['this_info']['intro'] : '';
    $data['image']        = $res['this_info']['image'] ? getimg($res['this_info']['image'])[0] : '';
    $data['content']      = $res['this_info']['content'] ? $res['this_info']['content'] : '';
    $data['prov_id']      = $res['prov_id'] ? $res['prov_id'] : '';
    $data['next_id']      = $res['next_id'] ? $res['next_id'] : '';
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'about_us.team');
$this->info['summary'] = '关于我们-倍沃团队';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('type', 'offset', 'limit');
    //输出参数
    $this->fields         = array('list' => ['tbid#1', 'name', 'title#1', 'intro', 'image'], 'count');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_about_us_team($param['type']);
    $data['count']        = $res['count'];
    $data['list']         = [];
    if ($res) {
        foreach ($res['list'] as $v) {
            $x              = [];
            $x['tbid']      = $v['tbid'] ? $v['tbid'] : '';
            $x['name']      = $v['name'] ? $v['name'] : '';
            $x['title']     = $v['title'] ? $v['title'] : '';
            $x['intro']     = $v['intro'] ? $v['intro'] : '';
            $x['image']     = $v['image'] ? getimg($v['image'])[0] : '';
            $data['list'][] = $x;
        }
    }
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'about_us_1');
$this->info['summary'] = '关于我们-关于倍沃';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array(['offset', 0], ['limit', 0]);
    //输出参数
    $this->fields         = array('intro', 'list' => ['image', 'name', 'english_name', 'content#1'],
        'count', 'set_up_time', 'pro_count', 'provinces_count', 'year_count', 'content#1', 'lng', 'lat', 'patent_for_invention', 'new_utility_patents', 'national_standard', 'Industry_technology'
    );
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    if (isset($param['offset']) && !empty($param['offset']) && isset($param['limit']) && !empty($param['limit'])) {
        $res = $that->get_about_us_1($param['offset'], $param['limit']);
    } else {
        $res = $that->get_about_us_1();
    }
    $info          = $res['info'];
    $list          = $res['list'];
    $data['intro'] = $info ? $info['intro'] : '';
    $data['list']  = [];
    if ($list) {
        foreach ($list as $v) {
            $x                 = [];
            $x['image']        = $v['image'] ? getimg($v['image'])[0] : '';
            $x['name']         = $v['name'] ? $v['name'] : '';
            $x['english_name'] = $v['english_name'] ? $v['english_name'] : '';
            $x['content']      = $v['content'] ? $v['content'] : '';
            $data['list'][]    = $x;
        }
        $data['count'] = $list['count'] ? $list['count'] : 0;
    }
    $data['set_up_time']          = $info['set_up_time'] ? $info['set_up_time'] : '';
    $data['pro_count']            = $info['pro_count'] ? $info['pro_count'] : '';
    $data['provinces_count']      = $info['provinces_count'] ? $info['provinces_count'] : '';
    $data['year_count']           = $info['year_count'] ? $info['year_count'] : '';
    $data['content']              = $info['content'] ? $info['content'] : '';
    $data['lng']                  = $info['lng'] ? $info['lng'] : '';
    $data['lat']                  = $info['lat'] ? $info['lat'] : '';
    $data['patent_for_invention'] = $info['patent_for_invention'] ? $info['patent_for_invention'] : '';
    $data['new_utility_patents']  = $info['new_utility_patents'] ? $info['new_utility_patents'] : '';
    $data['national_standard']    = $info['national_standard'] ? $info['national_standard'] : '';
    $data['Industry_technology']  = $info['Industry_technology'] ? $info['Industry_technology'] : '';
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'server_info');
$this->info['summary'] = '服务详情';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('tbid');
    //输出参数
    $this->fields         = array('title', 'content');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_server_info($param['tbid']);
    $data                 = [];
    $data['title']        = $res['title'];
    $data['content']      = $res['content'];
    //输出返回数据
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'server_list');
$this->info['summary'] = '服务列表';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('service_type');
    //输出参数
    $this->fields         = array('list' => ['tbid', 'title']);
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $res                  = $that->get_server_list($param['service_type']);
    $data['list']         = [];
    foreach ($res as $v) {
        $x              = [];
        $x['tbid']      = $v['tbid'];
        $x['title']     = $v['title'];
        $data['list'][] = $x;
    }
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'understandUs');
$this->info['summary'] = '关于我们';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('xInt', 'list' => array('xName', 'xEng', 'xImage', 'xContent'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->getInfoUnderstandUs();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'contentUs');
$this->info['summary'] = '联系我们';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('xTitle', 'xServ', 'xName', 'xAddr', 'xTelA', 'xTel', 'xFax', 'xEma',
        'list' => array('xName', 'xEng', 'list' => array('xName', 'xAddr', 'xTelA', 'xTel', 'xFax', 'xEma')));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->getInfoContentUs();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'content_us_agent_directory');
$this->info['summary'] = '销售网络';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('category_id');
    //输出参数
    $this->fields         = array('xName', 'xEng', 'arr_info' => array('xName', 'xProvince', 'xAddr', 'xTelA', 'xTel', 'xFax', 'xEma', 'xAddrLng', 'xAddrLat'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->getInfoContentUs1($param['category_id']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();
