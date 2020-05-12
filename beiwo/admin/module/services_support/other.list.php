<?php
require '../../../global.php';
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('services_support.support_browse');
?>
<!DOCTYPE html>
<html>
<head>
    <?php require 'admin/_head.php'; ?>
    <title>Banner管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
<!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
<!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
<!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
<input type="hidden" id="authorize-edit"
       value="<?php echo checkAdminAuthorize('services_support.support_edit') ? 1 : 0; ?>">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?php if(isset($_GET['m'])) echo '<button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>';?>其他</h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div id="toolbar" class="form-inline">
                        <div class="form-group">
                            <button type="button" id="add" class="btn btn-primary m-b-none authorize-edit"
                                    style="margin-bottom:0px">
                                <i class="glyphicon glyphicon-plus"></i> 新增
                            </button>
                        </div>
                    </div>
                    <table id="table" data-toggle="table"
                           data-url="other.ajax.php?ac=list"
                           data-query-params="queryParams" data-classes="table table-hover table-condensed"
                           data-striped="true" data-pagination="true" data-side-pagination="server"
                           data-page-list="[10, 20]" data-search="true" data-show-refresh="true"
                           data-toolbar="#toolbar">
                        <thead>
                        <tr>
                            <th data-field="indexid" data-halign="center" data-align="center" data-width="100">排序</th>
                            <th data-field="title" data-halign="center" data-align="center" data-width="200">名称</th>
                            <!-- <th data-field="createtime" data-sortable="true" data-halign="center" data-align="center"
                                data-width="150">发布时间
                            </th> -->
                            <th data-field="edittime" data-sortable="true" data-halign="center" data-align="center" data-width="150">编辑时间
                            </th>
                            <!-- <th data-field="is_use" data-halign="center" data-align="center">展示</th> -->
                            <th data-field="do" data-halign="center" data-align="center" data-width="150">操作</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'admin/_js.php'; ?>
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
    //放大镜
    $("body").on("click", ".image", function () {
        var img = new Image(); //图片预加载
        img.src = $(this).attr('src');//获取图片
        var s_w = screen.availWidth;//屏幕宽度
        var s_h = screen.availHeight;//屏幕高度
        var i_w = img.width;//加载的图片宽度
        var i_h = img.height;//加载的图片高度
        var u_w = 0;//实际展示的图片宽度
        var u_h = 0;//实际展示的图片高度
        //图片宽或高超出屏幕宽或高的任意尺寸的0.6(比例随意)
        if (i_w > s_w * 0.6 || i_h > s_h * 0.6) {
            //图片宽度超出比例大于高度超出比例
            if (i_w / s_w > i_h / s_h) {
                u_w = s_w * 0.6;
                u_h = s_w * 0.6 / i_w * i_h;
            } else {
                //图片高度超出比例大于宽度超出比例
                u_w = s_h * 0.6 / i_h * i_w;
                u_h = s_h * 0.6;
            }
        } else {
            u_w = i_w;
            u_h = i_h;
        }
        //先关闭所有的页面层
        layer.closeAll('page');
        //在弹出新的页面层
        layer.open({
            type: 1,//1页面层
            title: false,//标题
            closeBtn: 0,//关闭按钮
            area: [u_w + 'px', u_h + 'px'],
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,//开启遮罩
            content: '<img src="' + $(this).attr("src") + '" width="' + u_w + '" height="' + u_h + '" />'
        });
    });
    $(function () {
        var closeLayer = function () {
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            try {
                parent.closeLayer(index);
            } catch (err) {
                location.href = 'category.list.php';
            }
        }
        $('.closelayer').click(function (event) {
            closeLayer();
        });

        $('#table').on('click', '.edit', function (event) {
            event.preventDefault();
            var id = $(this).data().id;
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'other.edit.php?id=' + id
            });
        })
            .on('click', '.remove', function (event) {
                event.preventDefault();
                var id = $(this).data().id;
                swal({
                    title: "您确定要删除该Banner吗",
                    text: "删除后将无法恢复，请谨慎操作！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "是的，我要删除！",
                    cancelButtonText: "我再考虑一下…",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function () {
                    $.ajax({
                        url: 'other.ajax.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            ac: 'remove',
                            id: id
                        }
                    }).done(function () {
                        swal("删除成功！", "", "success");
                        $('#table').bootstrapTable('refresh', {silent: true});
                    }).fail(function () {
                        swal("删除失败！", "请重新操作！", "error");
                    });
                });
            });
        $('#add').click(function (event) {
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'other.edit.php'
            });
        });
        multifiltersInit(function () {
            $('#table').bootstrapTable('refresh', {silent: true});
        });
    });

    function closeLayer(index) {
        layer.close(index);
        $('#table').bootstrapTable('refresh', {silent: true});
    }

    function queryParams(params) {
        // params['banner_category_id'] = $('#quicksearch_category').val();
        return params;
    }
</script>
</body>
</html>
