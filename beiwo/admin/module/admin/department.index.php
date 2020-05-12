<?php
use inc\Authorize;

require('../../../global.php');
//进入页面后先判断是否有访问权限
Authorize::checkAdminAuthorizeAndJump('department.browse');
$name = $db->get('tb_admin_department','name',array('tbid'=>$_GET['id']));
$zw = $db->get('tb_admin_department','*',array('parent_lev'=>$_GET['id']));
// print_r($_GET['id']);die;
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>部门管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
    <!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
    <!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
    <input type="hidden" id="authorize-edit" value="<?php echo Authorize::checkAdminAuthorize('department.browse') ? 1 : 0; ?>">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                 <h2>
                 <button type="button" class="btn btn-white btn-sm closelayer">返回</button><?php echo '「'.$name.'」';?>部门管理</h2>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <div id="toolbar" class="btn-group">
                            <button type="button" id="add" class="btn btn-primary authorize-edit">
                                <i class="glyphicon glyphicon-plus"></i> 新增
                            </button>
                        </div>
                        <table id="table" data-toggle="table" data-url="department.ajax.php?ac=listJob&parent_lev=<?php echo $_GET['id']?>" data-classes="table table-hover table-condensed" data-striped="true" data-pagination="true" data-page-size="20" data-side-pagination="server" data-page-list="[20, 40]" data-search="true" data-show-refresh="true" data-show-toggle="false" data-toolbar="#toolbar">
                            <thead>
                                <tr>
                                    <th data-field="name" data-align="center">名称</th>
                                    <th data-field="do" data-halign="center" data-align="center" data-width="200">操作</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <!-- 自定义js -->
    <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
    <script>
    //此处为权限操作专属区域，禁止写其它业务逻辑代码
    $(function(){
        if($('#authorize-edit').val() == 0){
            $('.authorize-edit').hide();
        }
    });
    </script>
    <!-- Bootstrap table -->
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table-locale-all.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script>
    $(function(){
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.closeLayer(index);
        }
        $('.closelayer').click(function(event){
            closeLayer();
        });
        $('#table').on('click', '.edit', function(event){
            event.preventDefault();
            var id = $(this).data().id;
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: "department.pedit.php?parent_lev=<?php echo $_GET['id']?>&id="+id
            });
        })
        $('#add').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: "department.pedit.php?parent_lev=<?php echo $_GET['id']?>"
            });
        });
    });
    function closeLayer(index){
        layer.close(index);
        $('#table').bootstrapTable('refresh', {silent: true});
    }
    </script>
</body>
</html>
