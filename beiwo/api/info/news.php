<?php

$this->subset_api = array('name' => '新闻');
if (isset($this->module)) {
    $that = $this->module;
    //配置公用参数
    $this->infoarr['list'] = array('type' => 'array', 'summary' => '列表数据');
    $this->infoarr['cId'] = array('type' => 'string', 'summary' => '分类Id');
    $this->infoarr['cName'] = array('type' => 'string', 'summary' => '分类名称');
    $this->infoarr['offset'] = array('type' => 'int', 'summary' => '起始页');
    $this->infoarr['limit'] = array('type' => 'int', 'summary' => '每页数量');
    $this->infoarr['xId'] = array('type' => 'string', 'summary' => 'Id');
    $this->infoarr['xImg'] = array('type' => 'string', 'summary' => '封面图');
    $this->infoarr['xName'] = array('type' => 'string', 'summary' => '名称');
    $this->infoarr['xLook'] = array('type' => 'int', 'summary' => '阅读量');
    $this->infoarr['xShowTime'] = array('type' => 'string', 'summary' => '发布时间');
    $this->infoarr['xBeg'] = array('type' => 'string', 'summary' => '有效期（开始）');
    $this->infoarr['xEnd'] = array('type' => 'string', 'summary' => '有效期（结束）');
    $this->infoarr['xSource'] = array('type' => 'string', 'summary' => '来源');
    $this->infoarr['xAuthor'] = array('type' => 'string', 'summary' => '作者');
    $this->infoarr['xIntro'] = array('type' => 'string', 'summary' => '简介');
    $this->infoarr['xContent'] = array('type' => 'string', 'summary' => '内容');
    $this->infoarr['count'] = array('type' => 'string', 'summary' => '总数');
    $this->infoarr['isBanner'] = array('type' => 'int', 'summary' => '是否是Banner位的内容', 'list' => array('1' => '是'));
    $this->infoarr['xTop'] = array('type' => 'int', 'summary' => '置顶标签', 'list' => array('1' => '有', '0' => '无'));
    $this->infoarr['xHot'] = array('type' => 'int', 'summary' => '热门标签', 'list' => array('1' => '有', '0' => '无'));
    $this->infoarr['previousId'] = array('type' => 'string', 'summary' => '上一个id');
    $this->infoarr['previousName'] = array('type' => 'string', 'summary' => '上一个标题');
    $this->infoarr['previousTime'] = array('type' => 'string', 'summary' => '上一个日期');
    $this->infoarr['nextId'] = array('type' => 'string', 'summary' => '下一个id');
    $this->infoarr['nextName'] = array('type' => 'string', 'summary' => '下一个标题');
    $this->infoarr['nextTime'] = array('type' => 'string', 'summary' => '下一个日期');
}


$this->info = array('req' => 'cList');
$this->info['summary'] = '分类列表';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array();
    //输出参数
    $this->fields = array('list' => array('cId', 'cName'));
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //获取活动列表
    $data = $that->cList();
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info = array('req' => 'xList');
$this->info['summary'] = '列表';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array('cId', 'offset', 'limit', array('isBanner', 0));
    //输出参数
    $this->fields = array(
        'list' => array('xId', 'xImg', 'xName', 'cName', 'xIntro', 'xLook', 'xShowTime', 'xBeg', 'xEnd', 'xTop', 'xHot'), 'count');
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //校验广告分类Id
    if ($param['cId'] > 0) {
        $that->checkId('news_category', $param['cId']);
    }
    //获取列表
    $data = $that->iList($param['cId'], $param['offset'], $param['limit'], $param['isBanner']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();


$this->info = array('req' => 'xInfo');
$this->info['summary'] = '单个';
if ($this->checkthisapi()) {
    //数据提交模式
    $this->info['method'] = 'GET';
    //输入参数
    $this->parameter = array('xId');
    //输出参数
    $this->fields = array('xName', 'xShowTime', 'xSource', 'xAuthor', 'xIntro', 'xContent', 'xLook', 'xBeg', 'xEnd', 'previousId', 'previousName', 'previousTime', 'nextId', 'nextName', 'nextTime');
    //初始化接口参数，返回输入参数
    $param = $this->apiinit();
    //校验广告分类Id
    $that->checkId('news', $param['xId']);
    //获取列表
    $data = $that->info($param['xId']);
    //输出返回数据
    $this->echodata($data);
}
//添加所有接口参数
$this->addsubset();
