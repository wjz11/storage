<?php

require('../global.php');

?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>管理控制台 - <?php echo getconfig('title'); ?>管理后台</title>
    <base target="_blank">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <i class="fa fa-magic"></i> 注意到顶部的标签了么？当你每打开一个新板块，就会在顶部新增一个标签，你可以通过切换标签来切换显示页面，双击标签可以刷新页面哦。
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div id="memberCard" class="ibox float-e-margins">
                    <?php
                        $admin = $db->get('tb_admin_member', '*', array('tbid' => session('admin_id')));
                        $admin['avatar'] = file_exists('uploads/admin/'.session('admin_id').'/avatar.jpg') ? SITEROOTURL.'uploads/admin/'.session('admin_id').'/avatar.jpg' : 'img/avatar.jpg';
                        $loginlog = $db->get('tb_admin_member_login_log', '*', array(
                            'AND' => array(
                                'admin_member_id' => session('admin_id'),
                                'createtime[<]' => $db->get('tb_admin_member_login_log', 'createtime', array(
                                    'admin_member_id' => session('admin_id'),
                                    'ORDER' => array(
                                        'createtime' => 'DESC'
                                    )
                                ))
                            ),
                            'ORDER' => array(
                                'createtime' => 'DESC'
                            )
                        ));
//                        $ip = json_decode(file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.long2ip($loginlog['ip'])));
                        $ip['code']=1;
//                        if($ip->code == '1'){
                        if($ip['code'] == '1'){
                            $loginlog['info'] = 'N/A';
                        }else{
                            $loginlog['info'] = $ip->data->region.$ip->data->city;
                        }
                    ?>
                    <div class="ibox-title">
                        <h3>我的信息</h3>
                    </div>
                    <div class="ibox-content" style="height: 470px;">
                        <div class="row">
                            <div class="col-xs-2 text-center">
                                <img alt="头像" class="img-circle img-hdicon m-b-sm" src="<?php echo $admin['avatar']; ?>" height="64" width="64">
                                <h4>嗨，<?php echo
                                    $admin['username']; ?></h4>
                                <small>欢迎回来 !</small>
                            </div>
                            <div class="col-xs-5" style="margin-top:10px">
                                <h5>本次登录信息：</h5>
                                <h2 class="city" style="padding-left:20px">加载中...</h2>
                                <font style="padding-left:20px">( IP地址：<span class="ip">加载中...</span> )</font>
                            </div>
                            <div class="col-xs-5" style="margin-top:10px">
                                <h5>上次登录信息：</h5>
                                <div class="m-b-xs">
                                    <small><i class="fa fa-fw fa-map-marker"></i> <?php echo $loginlog['info']; ?></small>
                                </div>
                                <div class="m-b-xs">
                                    <small><i class="fa fa-fw fa-clock-o"></i> <?php echo $loginlog['createtime']; ?></small>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <small>如果不是你登录，请立即</small>
                                        <a href="javascript:;" class="btn btn-default btn-xs" onclick="javascript:parent.outsideMenuItem('module/personal/password.php', '修改密码');">修改密码</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3>快捷入口</h3>
                    </div>
                    <div class="ibox-content" style="height: 470px;">
                        <div class="row">
                            <!-- <div class="col-xs-6">
                                <a  class="btn btn-default btn-md btn-outline btn-block" onclick="javascript:parent.outsideMenuItem('module/connectUs/index.php', '联系我们');"><i class="fa fa-plus"></i> 联系我们</a>
                            </div> -->


                            <div class="col-xs-8">
                                <a  class="btn btn-default btn-md btn-outline btn-block" onclick="javascript:parent.outsideMenuItem('module/WxProduct/WxProduct.list.php', '微信端产品详情链接地址');"><i class="fa fa-picture-o"></i> 微信端产品详情链接地址</a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <script src="http://pv.sohu.com/cityjson?ie=utf-8" charset="utf-8"></script>
    <!-- 自定义js -->
    <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/echarts/echarts.min.js" charset="utf-8"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/arttemplate/template.js"></script>
    <script type="text/javascript">
        $(function(){
            if(returnCitySN != undefined){
                $('#memberCard .city').text(returnCitySN.cname);
                $('#memberCard .ip').text(returnCitySN.cip);
            }
            $('#dataCard select.choose').change(function(event) {
                var type = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: 'main.ajax.php',
                    dataType: 'json',
                    data: {
                        ac: 'data_info',
                        type: type
                    }
                }).done(function(cb){
                    $('#dataCard .data_info').html(template('dataTemp', cb));
                });
            });

        });
    </script>
</body>
</html>
