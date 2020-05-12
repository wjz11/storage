<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('website.log');
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>操作日志 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
<!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
<!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
<!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
<input type="hidden" id="authorize-edit" value="<?php echo checkAdminAuthorize('department.browse') ? 1 : 0; ?>">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2>操作日志</h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <table id="table" data-toggle="table" data-url="log.ajax.php?ac=list" data-classes="table table-hover table-condensed" data-striped="true" data-pagination="true" data-page-size="20" data-side-pagination="server" data-page-list="[20, 40]" data-search="true" data-show-refresh="true" data-show-toggle="false" data-toolbar="#toolbar">
                        <thead>
                        <tr>
                            <th data-field="name"  data-sortable="true">登陆者</th>
                            <th data-field="edittime"  data-sortable="true">编辑时间</th>
                            <th data-field="intro"  data-sortable="true">行为</th>
                            <th data-field="type"  data-sortable="true">对象类型</th>
                            <th data-field="tables"  data-sortable="true">操作对象（数据表）</th>
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
        $(document).on('click','.edit',function(event){
            event.preventDefault();
            var id = $(this).data().id;
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'fankui.edit.php?id='+id
            });
        });

        $(document).on('click','.status',function(event){
            var id = $(this).data().id;
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    ac: 'status',
                    id: id,
                }
            }).done(function(){
                swal("操作成功！", "", "success");
                $('#table').bootstrapTable('refresh', {silent: true});
            }).fail(function(){
                swal("操作失败！", "请重新操作！", "error");
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
