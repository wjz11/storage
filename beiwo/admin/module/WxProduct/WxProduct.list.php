<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
//checkAdminAuthorize('ice_order.browse');

?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>微信端产品详情列表- <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
    <!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
    <!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
    <input type="hidden" id="authorize-edit" value="1
    ">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h2>微信端产品详情列表</h2>
            </div>
            <!-- <div class="ibox-content"> -->
                <!-- <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-xs pull-right m-t-xs searchplusswitch"><span>展开筛选</span> <i class="fa fa-angle-down"></i></button>
                        <h3 class="m-b-xs">高级搜索</h3>
                    </div>
                </div> -->
                
            <!-- </div> -->
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <div id="toolbar" class="form-inline">
                            <div class="form-group">
                                <a href="javascript:;" id="export" class="btn btn-primary " style="margin-bottom:0">导出Excel</a>
                            </div>
                        </div>
                        <table id="table" data-toggle="table" data-url="ajax.php?ac=listWxProduct" data-query-params="queryParams" data-classes="table table-hover table-condensed" data-striped="true" data-pagination="true" data-side-pagination="server" data-page-list="[10, 20]" data-toolbar="#toolbar">
                            <thead>
                                <tr>
                                     <th data-field="name"  data-halign="center" data-align="center" data-width="100">名称</th>
                                    <th data-field="item_number" data-halign="center" data-align="center" data-width="150">货号</th>
                                    <th data-field="url" data-halign="center" data-align="center" data-width="200">链接地址</th>
                                    
                                    <!-- <th data-field="last" data-sortable="false" data-halign="center" data-width="200">最后操作</th>
                                    <th data-field="do" data-halign="center" data-align="center" data-width="250">操作</th> -->
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
        $("#export").click(function(){
            location.href = 'wxproduct.download.php';
        });
        //$('.search input').attr({"placeholder":"演出名称"});
        $('#table').on('click', '.remove', function(event){
            event.preventDefault();
            var id = $(this).data().id;
            $.ajax({
                url: 'show.ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    ac: 'status',
                    id: id
                }
            }).done(function () {
                swal("操作成功！", "", "success");
                $('#table').bootstrapTable('refresh', {silent: true});
            }).fail(function () {
                swal("操作失败！", "请重新操作！", "error");
            });
        });
        $('#add').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'iceorder.edit.php'
            });
        });
        multifiltersInit(function(){
            $('#table').bootstrapTable('refresh', {silent: true});
        });
    });
    function closeLayer(index){
        layer.close(index);
        $('#table').bootstrapTable('refresh', {silent: true});
    }
    function queryParams(params){
        params['status'] = $('#quicksearch_category').val();
        params['search'] = $('.search input').val();
        return params;
    }
    </script>
</body>
</html>
