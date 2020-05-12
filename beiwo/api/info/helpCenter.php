<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->subset_api = array('name' => '帮助中心');
if (isset($this->module))
{
    $that = $this->module;
    //配置公用参数
    $this->infoarr['search'] = array('type' => 'string', 'summary' => '搜索关键字');
    $this->infoarr['offset'] = array('type' => 'string', 'summary' => '起始页');
    $this->infoarr['limit'] = array('type' => 'string', 'summary' => '页长');
    $this->infoarr['list'] = array('type' => 'array', 'summary' => '列表数据');
    $this->infoarr['xName'] = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['xSize'] = array('type' => 'string', 'summary' => '大小');
    $this->infoarr['xDown'] = array('type' => 'string', 'summary' => '下载次数');
    $this->infoarr['xUrl'] = array('type' => 'string', 'summary' => '链接');
    $this->infoarr['xQuestion'] = array('type' => 'string', 'summary' => '问题');
    $this->infoarr['xAnswer'] = array('type' => 'string', 'summary' => '答案');
    $this->infoarr['cId'] = array('type' => 'string', 'summary' => '注意事项分类id');
    $this->infoarr['cName'] = array('type' => 'string', 'summary' => '注意事项分类名称');
    $this->infoarr['cEng'] = array('type' => 'string', 'summary' => '注意事项分类名称英文');
    $this->infoarr['cUrl'] = array('type' => 'string', 'summary' => '注意事项分类图片链接');
    $this->infoarr['cLogo'] = array('type' => 'string', 'summary' => 'logo链接');
    $this->infoarr['xOrderId'] = array('type' => 'string', 'summary' => '排序Id(越小越靠前，1最小)');
    $this->infoarr['xContent'] = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['xType'] = array('type' => 'string', 'summary' => '文件类型');
    $this->infoarr['count'] = array('type' => 'int', 'summary' => '统计');
    $this->infoarr['cImg'] = array('type' => 'string', 'summary' => '图片');
}


$this->info = array('req' => 'cList2');
$this->info['summary'] = '说明书分类';
if ($this->checkthisapi())
{
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array();
    //输出参数
    $this->fields = array('list' => array('cId', 'cName', 'cEng', 'cLogo', 'cImg'));
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //获取活动列表
    $data = $that->cList2();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info = array('req' => 'xList');
$this->info['summary'] = '说明书列表';
if ($this->checkthisapi())
{
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array(array('search', 0), array('cId', 0), array('offset', 0), array('limit', 0));
    //输出参数
    $this->fields = array('list' => array('xName', 'xSize', 'xDown', 'xUrl', 'xType'), 'count');
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //获取活动列表
    $data = $that->xList($param);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info = array('req' => 'problemList');
$this->info['summary'] = '常见问题列表';
if ($this->checkthisapi())
{
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array(array('search', 0), array('offset', 0), array('limit', 0));
    //输出参数
    $this->fields = array('list' => array('xQuestion', 'xAnswer'));
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //获取活动列表
    $data = $that->problemList($param);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info = array('req' => 'cList');
$this->info['summary'] = '注意事项分类';
if ($this->checkthisapi())
{
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array();
    //输出参数
    $this->fields = array('list' => array('cId', 'cName', 'cEng', 'cUrl'));
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //获取活动列表
    $data = $that->cList();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info = array('req' => 'xInfoList');
$this->info['summary'] = '注意事项列表';
if ($this->checkthisapi())
{
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array('cId');
    //输出参数
    $this->fields = array('list' => array('xOrderId', 'xContent'));
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //获取活动列表
    $data = $that->xInfoList($param);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


