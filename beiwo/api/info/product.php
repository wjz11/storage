<?php

$this->subset_api = array('name' => '产品');
if (isset($this->module)) {
    $that                             = $this->module;
    //配置公用参数
    $this->infoarr['list']            = array('type' => 'array', 'summary' => '列表数据');
    $this->infoarr['cId']             = array('type' => 'int', 'summary' => '分类Id(0全部)');
    $this->infoarr['cName']           = array('type' => 'string', 'summary' => '分类名称');
    $this->infoarr['cNameE']          = array('type' => 'string', 'summary' => '分类名称(英文)');
    $this->infoarr['cImage']          = array('type' => 'string', 'summary' => '分类图片');
    $this->infoarr['cChild']          = array('type' => 'string', 'summary' => '二级分类名');
    $this->infoarr['cIntro']          = array('type' => 'string', 'summary' => '简介');
    $this->infoarr['offset']          = array('type' => 'string', 'summary' => '起始页');
    $this->infoarr['limit']           = array('type' => 'string', 'summary' => '页长');
    $this->infoarr['offset1']         = array('type' => 'string', 'summary' => '起始页(分类)');
    $this->infoarr['limit1']          = array('type' => 'string', 'summary' => '页长(分类)');
    $this->infoarr['offset2']         = array('type' => 'string', 'summary' => '起始页(商品)');
    $this->infoarr['limit2']          = array('type' => 'string', 'summary' => '页长(商品)');
    $this->infoarr['xId']             = array('type' => 'int', 'summary' => 'Id');
    $this->infoarr['xName']           = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['xNameE']          = array('type' => 'string', 'summary' => '名称(英)');
    $this->infoarr['xNum']            = array('type' => 'string', 'summary' => '货号');
    $this->infoarr['xSpec']           = array('type' => 'string', 'summary' => '规格');
    $this->infoarr['xIntro']          = array('type' => 'string', 'summary' => '描述');
    $this->infoarr['xDesc']           = array('type' => 'string', 'summary' => '产品说明');
    $this->infoarr['xQues']           = array('type' => 'string', 'summary' => 'Q&A');
    $this->infoarr['xAtte']           = array('type' => 'string', 'summary' => '注意事项');
    $this->infoarr['xLook']           = array('type' => 'string', 'summary' => '浏览量');
    $this->infoarr['image']           = array('type' => 'array', 'summary' => '大图');
    $this->infoarr['image_sm']        = array('type' => 'array', 'summary' => '小图');
    $this->infoarr['trait']           = array('type' => 'string', 'summary' => '产品说明');
    $this->infoarr['xcName']          = array('type' => 'string', 'summary' => '分类名称');
    $this->infoarr['content']         = array('type' => 'string', 'summary' => '分类内容');
    $this->infoarr['good_id']         = array('type' => 'string', 'summary' => '商品id');
    $this->infoarr['good_name']       = array('type' => 'string', 'summary' => '商品名称');
    $this->infoarr['category_name']   = array('type' => 'string', 'summary' => '分类名称');
    $this->infoarr['good_image']      = array('type' => 'string', 'summary' => '商品图片');
    $this->infoarr['good_look']       = array('type' => 'string', 'summary' => '商品访问量');
    $this->infoarr['search']          = array('type' => 'string', 'summary' => '搜索内容');
    $this->infoarr['search1']         = array('type' => 'string', 'summary' => '搜索内容（分类名）');
    $this->infoarr['search2']         = array('type' => 'string', 'summary' => '搜索内容（商品名）');
    $this->infoarr['sku_info']        = array('type' => 'array', 'summary' => 'sku信息');
    $this->infoarr['goods_id']        = array('type' => 'int', 'summary' => '商品id');
    $this->infoarr['article_number']  = array('type' => 'string', 'summary' => '货号');
    $this->infoarr['specifications']  = array('type' => 'string', 'summary' => '规格');
    $this->infoarr['list_price']      = array('type' => 'int', 'summary' => '目录价（分）');
    $this->infoarr['promotion_price'] = array('type' => 'int', 'summary' => '促销价（分）');
    $this->infoarr['file_name']       = array('type' => 'string', 'summary' => '文件名称');
    $this->infoarr['file_size']       = array('type' => 'string', 'summary' => '文件大小');
    $this->infoarr['file_count']      = array('type' => 'string', 'summary' => '文件下载统计');
    $this->infoarr['file_url']        = array('type' => 'string', 'summary' => '文件下载路径');
    $this->infoarr['time_begin']      = array('type' => 'string', 'summary' => '促销开始时间');
    $this->infoarr['time_end']        = array('type' => 'string', 'summary' => '促销结束时间');
    $this->infoarr['iscuxiao']        = array('type' => 'int', 'summary' => '是否促销', 'list' => array(1 => '是', 0 => '否'));
    $this->infoarr['count']           = array('type' => 'int', 'summary' => '总数');
    $this->infoarr['file_list']       = array('type' => 'array', 'summary' => '文件列表');
    $this->infoarr['s_list']          = array('type' => 'array', 'summary' => '列表');
    $this->infoarr['s_type']          = array('type' => 'int', 'summary' => '类型', 'list' => [0 => 'all', 1 => '说明书', 2 => '宣传资料', 3 => '其他']);
    $this->infoarr['s_id']            = array('type' => 'string', 'summary' => '服务id');
    $this->infoarr['title']           = array('type' => 'string', 'summary' => '标题');
    $this->infoarr['content']         = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['createtime']      = array('type' => 'string', 'summary' => '创建时间');
    $this->infoarr['edittime']        = array('type' => 'string', 'summary' => '编辑时间');
    $this->infoarr['url']             = array('type' => 'string', 'summary' => '链接地址');
    $this->infoarr['file']            = array('type' => 'string', 'summary' => '文件');
    $this->infoarr['url_type']        = array('type' => 'int', 'summary' => '类型', 'list' => [1 => '链接', 2 => '文件']);
    $this->infoarr['is_succ']         = array('type' => 'int', 'summary' => '是否成功', 'list' => [1 => '成功', 0 => '失败']);
    $this->infoarr['d_type']          = array('type' => 'int', 'summary' => '类型', 'list' => [1 => '资料', 2 => '产品']);
    $this->infoarr['d_id']            = array('type' => 'int', 'summary' => '对应的id，根据类型来(1传s_id，2传f_id)');
    $this->infoarr['f_id']            = array('type' => 'string', 'summary' => '文件id');
    $this->infoarr['file_type']       = array('type' => 'string', 'summary' => '文件类型');
    $this->infoarr['clist']           = array('type' => 'array', 'summary' => '系列列表');
    $this->infoarr['glist']           = array('type' => 'array', 'summary' => '商品列表');
    $this->infoarr['intro']           = array('type' => 'string', 'summary' => '简介');
    $this->infoarr['content']         = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['description']     = array('type' => 'string', 'summary' => '产品说明');
}

$this->info            = ['req' => 'list.server'];
$this->info['summary'] = '资料列表';
if ($this->checkthisapi()) {
    $this->info['method'] = 'GET';
    $this->parameter      = [['s_type', 0], ['search', 0], ['offset', 0], ['limit', 0]];
    $this->fields         = ['s_list' => ['s_id', 'title', 'content', 's_type', 'createtime', 'edittime', 'url_type', 'url', 'file', 'file_size', 'file_type']];
    $param                = $this->apiinit();
    $data                 = $that->server_list($param);
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();

$this->info            = ['req' => 'add.download'];
$this->info['summary'] = '增加下载次数';
if ($this->checkthisapi()) {
    $this->info['method'] = 'GET';
    $this->parameter      = ['d_id', 'd_type'];
    $this->fields         = ['is_succ'];
    $param                = $this->apiinit();
    $table                = $param['d_type'] == 1 ? 'server_text' : 'mannual';
    $data['is_succ']      = $that->add_download($table, $param);
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();

$this->info            = ['req' => 'info.server'];
$this->info['summary'] = '资料详情';
if ($this->checkthisapi()) {
    $this->info['method'] = 'GET';
    $this->parameter      = ['s_id'];
    $this->fields         = ['title', 'content', 's_type', 'createtime', 'edittime', 'url_type', 'url', 'file', 'file_size', 'file_type'];
    $param                = $this->apiinit();
    $data                 = $that->server_info($param);
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = ['req' => 'xInfo'];
$this->info['summary'] = '详情';
if ($this->checkthisapi()) {
    $this->info['method'] = 'GET';
    $this->parameter      = ['xId'];
    $this->fields         = [
        'xName', 'xNameE', 'xcName', 'xIntro', 'xDesc', 'xQues', 'xAtte', 'image', 'image_sm', 'cId', 'sku_info'  => [
            'goods_id', 'article_number', 'specifications', 'list_price', 'promotion_price'
        ], 'file_list' => [
            'f_id', 'file_name', 'file_size', 'file_count', 'file_url'
        ], 'time_begin', 'time_end', 'iscuxiao'];
    $param                = $this->apiinit();
    $data                 = $that->info($param);
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'cList');
$this->info['summary'] = '分类列表（产品目录顶部）';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('list' => array('cId', 'cName', 'cNameE', 'cImage', 'cChild'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'cList2');
$this->info['summary'] = '分类列表（二级分类）';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('cId', 'offset', 'limit');
    //输出参数
    $this->fields         = array('list' => array('cId', 'cName', 'cNameE', 'cImage', 'list' => ['xId', 'xName', 'xNameE']));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList2($param['cId'], $param['offset'], $param['limit']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'cList2.all');
$this->info['summary'] = '分类列表.二级分类.所有';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array(['search1', 0], ['search2', 0], ['offset1', 0], ['limit1', 0], ['offset2', 0], ['limit2', 0]);
    //输出参数
    $this->fields         = array('list' => array('cId', 'cName', 'cNameE', 'cImage', 'list' => ['xId', 'xName', 'xNameE', 'image_sm']), 'count');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList2_all($param['search1'], $param['search2'], $param['offset1'], $param['limit1'], $param['offset2'], $param['limit2']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'cList2.info');
$this->info['summary'] = '分类列表（二级分类详情）';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('cId');
    //输出参数
    $this->fields         = array('cId', 'cName', 'cNameE', 'cImage', 'content', 'file_name', 'file_size', 'file_count', 'file_url');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList2_info($param['cId']);
    //获取资源ID
    $fileinfo             = $this->db->get('mannual', '*', ['resource_id' => $param['cId'], 'resource_type' => 'goods_category']);
    if ($fileinfo) {
        $data['file_name']  = $fileinfo['name'];
        $data['file_size']  = $fileinfo['size'];
        $data['file_count'] = $fileinfo['count'];
        $data['file_url']   = getimg($fileinfo['file'], 1, 0);
    } else {
        $data['file_name']  = '';
        $data['file_size']  = '';
        $data['file_count'] = '';
        $data['file_url']   = '';
    }
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'cList3');
$this->info['summary'] = '新品推荐.系列';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('list' => array('cId', 'cName', 'cNameE', 'cImage', 'xLook'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList3();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'pro_new');
$this->info['summary'] = '新品推荐.商品';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array();
    //输出参数
    $this->fields         = array('list' => array('good_id', 'good_name', 'category_name', 'good_image', 'good_look'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList4();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();

$this->info            = array('req' => 'list.recommended');
$this->info['summary'] = '推荐.混合';
if ($this->checkthisapi()) {
    $this->info['method'] = 'GET';
    $this->parameter      = array();
    $this->fields         = array(
        'clist' => array('cId', 'cName', 'cNameE', 'cImage', 'xLook', 'intro', 'content'),
        'glist' => array('good_id', 'good_name', 'category_name', 'good_image', 'good_look', 'intro', 'description')
    );
    $param                = $this->apiinit();
    $data                 = $that->recommended_list();
    $this->echodata($data);
}
$this->addsubset();


$this->info            = array('req' => 'xList');
$this->info['summary'] = '产品列表';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array(array('cId', 0), array('search', 0), 'offset', 'limit');
    //输出参数
    $this->fields         = array('cId', 'cName', 'cNameE', 'cImage', 'cIntro',
        'list' => array('xId', 'xName', 'xNum', 'xSpec', 'xIntro'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->xList($param);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();
