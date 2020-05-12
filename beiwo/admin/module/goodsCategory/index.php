<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
inc\Authorize::checkAdminAuthorizeAndJump('goodsCategory.browse');
if (empty($_GET['cid']))
{
    $_GET['cid'] = 0;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('admin/_head.php'); ?>
        <title>产品中心信息管理 - <?php echo getconfig('title'); ?>管理后台</title>
        <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
        <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
        <style media="screen">
            .webuploader-container {
                position: relative;
            }

            .webuploader-element-invisible {
                position: absolute !important;
                clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                clip: rect(1px, 1px, 1px, 1px);
            }

            .webuploader-pick {
            }

            .webuploader-pick-hover {
            }

            .webuploader-pick-disable {
                opacity: 0.6;
                pointer-events: none;
            }
        </style>
    </head>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h2>
                        <?php
                        if (isset($_GET['m'])) { 
		                    echo '<button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>';
		                 }
                        if ($_GET['cid'] != 0)
                        {
                            echo '<button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>';
                            if ($_GET['cid'])
                            {
                                $name = $db->get('tb_goods_category', 'name', ['tbid' => $_GET['cid']]);
                                echo '「' . $name . '」产品分类管理';
                            }
                        }
                        else
                        {
                            echo '产品中心信息管理';
                        }
                        ?>

                    </h2>
                </div>
                <div class="ibox-content">
                    <div class="row row-lg">
                        <div class="col-sm-12">
                            <div id="toolbar" class="form-inline">
                                <?php
                                if (inc\Authorize::checkAdminAuthorize('goodsCategory.edit') && $_GET['cid'] != 0)
                                {
                                    echo '<button type="button" id="add" class="btn btn-primary authorize-edit">
                                            <i class="glyphicon glyphicon-plus"></i> 新增
                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                }
                                if(inc\Authorize::checkAdminAuthorize('goodsCategory.edit') && $_GET['cid'] != 3 && $_GET['cid']!=0){
                                    echo '<div class="form-group">
                                        <div class="btn-group" id="upload-group">
                                            <a href="javascript:;" id="upload" class="btn btn-primary" style="border-radius:3px;">导入当前分类商品</a>
                                        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div class="btn-group" id="upload-group1">
                                            <a href="javascript:;" id="upload1" class="btn btn-primary" style="border-radius:3px;">导入所有分类商品</a>
                                        </div>
                                    </div>';
                                }
            // if (inc\Authorize::checkAdminAuthorize('goodsCategory.edit') && $_GET['cid']==0)
            // {
            //     echo '<button type="button" id="add_edit" class="btn btn-primary add_edit">
            //             <i class="add_edit"></i> 首页产品分类信息编辑
            //         </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            // }
                                ?>

                            </div>
                            <input type="hidden" name="cid" id="cid" value="<?php echo $_GET['cid']; ?>">
                            <table id="table" data-toggle="table" data-url="ajax.php?ac=listTable&cid=<?php echo $_GET['cid']; ?>" data-classes="table table-hover table-condensed" data-striped="true" data-pagination="true" data-side-pagination="server" data-page-list="[10, 20]" data-search="true" data-show-refresh="true" data-show-toggle="false" data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th data-field="indexid" data-halign="center" data-align="center" data-width="100">排序</th>
                                        
                                        <?php  
                                            if($_GET['cid']==0){
                                                echo '<th data-field="name" data-halign="center" data-align="center" data-width="300">名称</th>';
                                            }
                                            if ($_GET['cid'] != 0 && $_GET['cid']!=3)
                                            {
                                                echo '<th data-field="name" data-halign="center" data-align="center" data-width="300">名称</th>
                                                    <th data-field="is_new" data-halign="center" data-align="center" >是否新品推荐</th>
                                                    <th data-field="is_item" data-halign="center" data-align="center" >是否特色推荐</th>';
                                            }
                                            if($_GET['cid']==3){
                                                echo '
                                                    <th data-field="tbid" data-halign="center" data-align="center" >资源id</th><th data-field="image_icon_1" data-halign="center" data-align="center" data-width="300">展示图标</th><th data-field="name" data-halign="center" data-align="center" data-width="300">分类名称</th><th data-field="english_name" data-halign="center" data-align="center" data-width="300">英文名称</th><th data-field="is_new" data-halign="center" data-align="center" >是否新品推荐</th>';
                                            }
                                        ?>
                                        
                                        <th data-field="do" data-halign="center" data-align="center" data-width="300">操作</th>
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
        <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.2"></script>
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
        <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/webuploader.min.js"></script>
        <script>
            $(function () {
                var closeLayer = function () {
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.closeLayer(index);
                }
                $('.closelayer').click(function (event) {
                    closeLayer();
                });

                //-----------导入当前分类商品----------
                var uploader = WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // swf文件路径
                    swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
                    // 文件接收服务端。
                    server: 'excel.php',
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {
                        id: '#upload',
                        multiple: false
                    },
                    accept: {
                        title: 'Excel',
                        extensions: 'xls,xlsx',
                        mimeTypes: '.xls,.xlsx',
                    },
                    compress: false
                });
                uploader.on('uploadProgress', function (file, percentage) {
                    var $progress = $('#progress');
                    //避免重复创建
                    if (!$progress.length) {
                        $progress = $('<a href="javascript:;" id="progress" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group');
                    }
                    $progress.text(percentage * 100 + '%');
                    swal({
                        title: "数据导入",
                        text: "正在数据请耐心等待",
                        type: "warning",
                        timeout: 1000 * 600,
                        // icon: "success",
                        showLoaderOnConfirm: true,
                        showConfirmButton: false,
                    });
                });
                uploader.on('uploadComplete', function (file) {
                    $('#progress').fadeOut(function () {
                        $(this).remove();
                    });
                });
                uploader.on('uploadSuccess', function (file, cb) {
                    if (cb.state == 'SUCCESS') {
                        swal(cb.error, '', 'success');
                        $('#table').bootstrapTable('refresh', {silent: true});
                    } else {
                        swal(cb.error, '', 'error');
                        $('#table').bootstrapTable('refresh', {silent: true});
                    }
                    uploader.removeFile(file);
                });
                uploader.on('uploadBeforeSend', function (object, data, header) {
                    // 修改data可以控制发送哪些携带数据。
                    data.cid = '<?php echo $_GET['cid']; ?>';
                    data.radioValue = 1;
                });
                //-----------导入当前分类商品----------
                //-----------导入所有分类商品----------
                var uploader1 = WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // swf文件路径
                    swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
                    // 文件接收服务端。
                    server: 'excel.php',
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {
                        id: '#upload1',
                        multiple: false
                    },
                    accept: {
                        title: 'Excel',
                        extensions: 'xls,xlsx',
                        mimeTypes: '.xls,.xlsx',
                    },
                    compress: false
                });
                uploader1.on('uploadProgress', function (file, percentage) {
                    var $progress1 = $('#progress1');
                    //避免重复创建
                    if (!$progress.length) {
                        $progress1 = $('<a href="javascript:;" id="progress1" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group1');
                    }
                    $progress1.text(percentage * 100 + '%');
                    swal({
                        title: "数据导入",
                        text: "正在数据请耐心等待",
                        type: "warning",
                        timeout: 1000 * 600,
                        // icon: "success",
                        showLoaderOnConfirm: true,
                        showConfirmButton: false,
                    });
                });
                uploader1.on('uploadComplete', function (file) {
                    $('#progress1').fadeOut(function () {
                        $(this).remove();
                    });
                });
                uploader1.on('uploadSuccess', function (file, cb) {
                    if (cb.state == 'SUCCESS') {
                        swal(cb.error, '', 'success');
                        $('#table').bootstrapTable('refresh', {silent: true});
                    } else {
                        swal(cb.error, '', 'error');
                        $('#table').bootstrapTable('refresh', {silent: true});
                    }
                    uploader1.removeFile(file);
                });
                uploader1.on('uploadBeforeSend', function (object, data, header) {
                    // 修改data可以控制发送哪些携带数据。
                    data.cid = '<?php echo $_GET['cid']; ?>';
                    data.radioValue = 2;
                });
                //-----------导入所有分类商品----------

                $('#table')
                        .on('click', '.edit', function (event) {
                            event.preventDefault();
                            var id = $(this).data().id;
                            var cid = $("#cid").val();
                            if(cid==3){
                                layer.open({
                                    type: 2,
                                    title: false,
                                    closeBtn: false,
                                    area: ['100%', '100%'],
                                    shade: false,
                                    move: false,
                                    content: 'category_edit.php?id=' + id+"&cid="+cid
                                });
                            }else{
                                layer.open({
                                    type: 2,
                                    title: false,
                                    closeBtn: false,
                                    area: ['100%', '100%'],
                                    shade: false,
                                    move: false,
                                    content: 'edit.php?id=' + id+"&cid="+cid
                                });
                            }
                            
                        })
                        .on('click', '.remove', function (event) {
                            event.preventDefault();
                            var id = $(this).data().id;
                            swal({
                                title: "您确定要删除该类目吗",
                                text: "删除后对应商品也会被删除，请谨慎操作！",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "是的，我要删除！",
                                cancelButtonText: "我再考虑一下…",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true
                            }, function () {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        ac: 'delete',
                                        id: id
                                    }
                                }).done(function () {
                                    swal("删除成功！", "", "success");
                                    $('#table').bootstrapTable('refresh', {silent: true});
                                }).fail(function () {
                                    swal("删除失败！", "请重新操作！", "error");
                                });
                            });
                        })
                        .on('click', '.status', function (event) {
                            event.preventDefault();
                            var id = $(this).data().id;
                            $.ajax({
                                url: 'ajax.php',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    ac: 'changeStatus',
                                    id: id,
                                    shopid: 1,
                                }
                            }).done(function () {
                                swal("操作成功！", "", "success");
                                $('#table').bootstrapTable('refresh', {silent: true});
                            }).fail(function () {
                                swal("操作失败！", "请重新操作！", "error");
                            });
                        }).on('click', '.resources_edit', function (event) {
                            event.preventDefault();
                            var id = $(this).data().id;
                            layer.open({
                                type: 2,
                                title: false,
                                closeBtn: false,
                                scrollbar: false,
                                area: ['100%', '100%'],
                                shade: false,
                                move: false,
                                content: 'resources_edit.php?id=' + id
                            });
                        })
                        .on('click', '.item', function (event) {
                            event.preventDefault();
                            var id = $(this).data().id;
                            layer.open({
                                type: 2,
                                title: false,
                                closeBtn: false,
                                area: ['100%', '100%'],
                                shade: false,
                                move: false,
                                content: 'index.php?cid=' + id
                            });
                        })
                        .on('click', '.goods', function (event) {
                            event.preventDefault();
                            var id = $(this).data().id;
                            layer.open({
                                type: 2,
                                title: false,
                                closeBtn: false,
                                area: ['100%', '100%'],
                                shade: false,
                                move: false,
                                content: '../goods/index.php?cid=' + id
                            });
                        });
                $('#add').click(function (event) {
                    event.preventDefault();
                    var cid = $("#cid").val();
                    if(cid==3){
                        layer.open({
                            type: 2,
                            title: false,
                            closeBtn: false,
                            area: ['100%', '100%'],
                            shade: false,
                            move: false,
                            content: 'category_edit.php?cid='+cid
                        });
                    }else{
                        layer.open({
                            type: 2,
                            title: false,
                            closeBtn: false,
                            area: ['100%', '100%'],
                            shade: false,
                            move: false,
                            content: 'edit.php?cid=<?php echo $_GET['cid']; ?>'
                        });
                    }
                    
                });
                $('#add_edit').click(function (event) {
                    event.preventDefault();
                    // var cid = $("#cid").val();
                    layer.open({
                        type: 2,
                        title: false,
                        closeBtn: false,
                        area: ['100%', '100%'],
                        shade: false,
                        move: false,
                        content: 'category_add_edit.php'
                    });
                    
                });
            });
            function closeLayer(index) {
                layer.close(index);
                $('#table').bootstrapTable('refresh', {silent: true});
            }
        </script>
    </body>
</html>
