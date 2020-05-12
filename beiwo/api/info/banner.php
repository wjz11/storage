<?php

$this->subset_api = array('name' => '广告');
if (isset($this->module)) {
    $that                         = $this->module;
    //配置公用参数
    $this->infoarr['list']        = array('type' => 'array', 'summary' => '列表数据');
    $this->infoarr['cId']         = array('type' => 'string', 'summary' => '分类Id');
    $this->infoarr['cName']       = array('type' => 'string', 'summary' => '分类名称');
    $this->infoarr['xId']         = array('type' => 'string', 'summary' => 'Id');
    $this->infoarr['xName']       = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['xImage']      = array('type' => 'string', 'summary' => '图片路径');
    $this->infoarr['xImage_h5']   = array('type' => 'string', 'summary' => '图片路径_h5');
    $this->infoarr['xUrl']        = array('type' => 'string', 'summary' => '链接');
    $this->infoarr['pid']         = array('type' => 'string', 'summary' => '父id,0顶级');
    $this->infoarr['type']        = array('type' => 'int', 'summary' => '类型', 'list' => [1 => '链接', 3 => '商品', 8 => '新闻资讯', 9 => '行业动态']);
    $this->infoarr['resource_id'] = array('type' => 'int', 'summary' => '资源id');
}


$this->info            = array('req' => 'cList');
$this->info['summary'] = '分类列表';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('pid');
    //输出参数
    $this->fields         = array('list' => array('cId', 'cName'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //获取活动列表
    $data                 = $that->cList($param['pid']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'xList');
$this->info['summary'] = '列表';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('cId');
    //输出参数
    $this->fields         = array('list' => array('xId', 'xName', 'xImage', 'xImage_h5', 'xUrl', 'type', 'resource_id'));
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //校验广告分类Id
    $that->checkId('banner_category', $param['cId']);
    //获取列表
    $data                 = $that->iList($param['cId']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info            = array('req' => 'xInfo');
$this->info['summary'] = '单个';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter      = array('xId');
    //输出参数
    $this->fields         = array('xId', 'xName', 'xImage', 'xImage_h5', 'xUrl', 'type', 'resource_id');
    //初始化接口参数，返回输入参数
    $param                = $this->apiinit();
    //校验广告分类Id
    $that->checkId('banner', $param['xId']);
    //获取列表
    $data                 = $that->info($param['xId']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();
