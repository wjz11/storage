<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('banner.category_browse');
$pid = $_GET['pid'] ? $_GET['pid'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>栏目管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
<!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
<!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
<!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
<input type="hidden" id="authorize-edit" value="<?php echo checkAdminAuthorize('banner.category_edit') ? 1 : 0; ?>">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2>
                <?php if ($pid > 0) { 

                    echo '<button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>';
                    if($pid==6 ){
                            echo 'PC官网关于我们子类管理';
                        }
                        if( $pid==4){
                            echo 'PC官网资料下载子类管理';
                        }
                
                } else{
                            echo '栏目管理';
                        }
                ?>
            </h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <table id="table" data-toggle="table"
                           data-url="category.ajax.php?ac=list&pid=<?php echo $pid; ?>&b=1"
                           data-query-params="queryParams" data-classes="table table-hover table-condensed"
                           data-striped="true" data-pagination="true" data-side-pagination="server"
                           data-page-list="[10, 20]" data-search="true" data-show-refresh="true"
                           data-toolbar="#toolbar">
                        <thead>
                        <tr>
                            <th data-field="name" data-halign="center" data-align="center">banner位名称</th>
                            <th data-field="do" data-halign="center" data-align="center" data-width="400">操作</th>
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
    $(function () {
        if ($('#authorize-edit').val() == 0) {
            $('.authorize-edit').hide();
        }
    });
</script>
<!-- Bootstrap table -->
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table-locale-all.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script>
    $(function () {
        var closeLayer = function () {
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.closeLayer(index);
        }
        $('.closelayer').click(function (event) {
            closeLayer();
        });
        $('#s_type').on('change', function () {
            $('#table').bootstrapTable('refresh');
        });
        $('#table')
            .on('click', '.edit', function (event) {
                event.preventDefault();
                var id = $(this).data().id;
                var str = $(this).parent().parent().children().eq(0).html()+" banner管理";
                layer.open({
                    type: 2,
                    title: false,
                    closeBtn: false,
                    area: ['100%', '100%'],
                    shade: false,
                    move: false,
                     content: 'banner.list.php?banner_category_id=' + id+'&str='+str
                });
            }).on('click', '.edit_c', function (event) {
                event.preventDefault();
                var id = $(this).data().id;
                var str = $(this).parent().parent().children().eq(0).html()+" banner管理";
                layer.open({
                    type: 2,
                    title: false,
                    closeBtn: false,
                    area: ['100%', '100%'],
                    shade: false,
                    move: false,
                    content: 'banner.php?pid=' + id+'&str='+str
                });
            })
            .on('click', '.child', function (event) {
                event.preventDefault();
                var id = $(this).data().id;
                layer.open({
                    type: 2,
                    title: false,
                    closeBtn: false,
                    area: ['100%', '100%'],
                    shade: false,
                    move: false,
                    content: 'category.list.php?pid=' + id
                });
            })
            .on('click', '.item', function (event) {
                event.preventDefault();
                var id = $(this).data().id;
                if(id==1){
                	layer.open({
	                    type: 2,
	                    title: false,
	                    closeBtn: false,
	                    area: ['100%', '100%'],
	                    shade: false,
	                    move: false,
	                    content: '../aboutUsItem/partners.list.php?m=1'
	                });
                }else if(id==2){
                	layer.open({
	                    type: 2,
	                    title: false,
	                    closeBtn: false,
	                    area: ['100%', '100%'],
	                    shade: false,
	                    move: false,
	                    content: '../goodsCategory/index.php?m=2'
	                });
                }else if(id==3){
                	layer.open({
	                    type: 2,
	                    title: false,
	                    closeBtn: false,
	                    area: ['100%', '100%'],
	                    shade: false,
	                    move: false,
	                    content: '../news/category.list.php?m=3'
	                });
                }else if(id==5){
                	layer.open({
	                    type: 2,
	                    title: false,
	                    closeBtn: false,
	                    area: ['100%', '100%'],
	                    shade: false,
	                    move: false,
	                    content: '../connectUsItem/category.list.php?m=5'
	                });
                }else if(id==6){
                	layer.open({
	                    type: 2,
	                    title: false,
	                    closeBtn: false,
	                    area: ['100%', '100%'],
	                    shade: false,
	                    move: false,
	                    content: '../aboutUsItem/index.php?m=6'
	                });
                }
                
            });
    });

    function closeLayer(index) {
        layer.close(index);
        $('#table').bootstrapTable('refresh', {silent: true});
    }

    function queryParams(params) {
        params['type'] = $('#s_type').val();
        return params;
    }
</script>
</body>
</html>
