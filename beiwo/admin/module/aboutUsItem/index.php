<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限 
inc\Authorize::checkAdminAuthorizeAndJump('aboutUs.edit');
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>关于我们管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
    <!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
    <!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
    <input type="hidden" id="authorize-edit" value="<?php echo inc\authorize::checkAdminAuthorize('aboutUs.edit') ? 1 : 0; ?>">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
           <div class="ibox-title">
                    <h2>
                    	<?php if (isset($_GET['m'])) { 
		                    echo '<button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>';
		                } ?>
                      关于我们管理
                    </h2>
                </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <table id="table" data-toggle="table"  data-classes="table table-hover table-condensed" data-striped="true"  data-page-size="20" data-side-pagination="server" data-page-list="[20, 40]" data-show-toggle="false" data-toolbar="#toolbar" class="table table-hover table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th data-halign="center" data-align="center" data-field="name" data-width="250">
                                        <div class="th-inner ">名称</div>
                                        
                                    </th>
                                    <th data-halign="center" data-align="center" data-field="do" data-width="150">
                                        <div class="th-inner ">操作</div>
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr data-index="0"> 
                                    <td data-halign="center" data-align="center">关于我们总括</td> 
                                    <td data-halign="center" data-align="center"> 
                                        <a href="javascript:;" class="btn btn-primary btn-xs edit1" data-id="10">编辑</a>  
                                    </td> 
                                </tr> -->
                                <tr data-index="1"> 
                                    <td data-halign="center" data-align="center">关于倍沃</td> 
                                    <td data-halign="center" data-align="center"> 
                                        <a href="javascript:;" class="btn btn-primary btn-xs edit1" data-id="10">信息编辑</a> 
                                    </td> 
                                </tr>
                                <tr data-index="2"> 
                                    <td data-halign="center" data-align="center">倍沃团队</td> 
                                    <td data-halign="center" data-align="center"> 
                                        <a href="javascript:;" class="btn btn-primary btn-xs edit3" data-id="10">信息编辑</a>  
                                    </td> 
                                </tr>
                                <!-- <tr data-index="3"> 
                                    <td data-halign="center" data-align="center">倍沃成就</td> 
                                    <td data-halign="center" data-align="center"> 
                                        <a href="javascript:;" class="btn btn-primary btn-xs edit4" data-id="10">编辑</a>   
                                    </td> 
                                </tr> -->
                                <tr data-index="4"> 
                                    <td data-halign="center" data-align="center">倍沃历程</td> 
                                    <td data-halign="center" data-align="center"> 
                                        <a href="javascript:;" class="btn btn-primary btn-xs edit5" data-id="10">信息编辑</a>
                                        <a href="javascript:;" class="btn btn-info btn-xs edit6" data-id="10">历程编辑</a>
                                    </td>  
                                </tr>
                            </tbody>
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
        $('#table').on('click', '.remove', function(event){
            event.preventDefault();
            var id = $(this).data().id;
            swal({
                title: "您确定要删除这条分项吗",
                text: "删除后将无法恢复，请谨慎操作！",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "是的，我要删除！",
                cancelButtonText: "我再考虑一下…",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function(){
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        ac: 'deleteItem',
                        id: id
                    }
                }).done(function(){
                    swal("删除成功！", "", "success");
                    $('#table').bootstrapTable('refresh', {silent: true});
                }).fail(function(){
                    swal("删除失败！", "请重新操作！", "error");
                });
            });
        })
        .on('click', '.edit1', function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: '../aboutUs/index.php'
            });
        });
        $('.edit2').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'category.list.php'
            });
        });
       $('.edit3').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'team.list.php'
            });
        });
       $('.edit4').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'achievement.list.php'
            });
        });
        $('.edit5').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'course.edit_info.php'
            });
        });
        $('.edit6').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'course.list.php'
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
