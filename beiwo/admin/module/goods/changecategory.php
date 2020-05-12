<?php
require('../../../global.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('admin/_head.php'); ?>
        <title>商品管理 - <?php echo getconfig('title'); ?>商家管理后台</title>
        <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/treeview/bootstrap-treeview.css" rel="stylesheet">
    </head>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>
                        <button type="button" class="btn btn-white btn-sm closelayer">返回</button>
                        选择产品类目
                    </h3>
                </div>
                <div class="ibox-content">
                    <div class="row row-lg">
                        <div class="col-sm-12">
                            <div id="treeview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require('admin/_js.php'); ?>
        <!-- 自定义js -->
        <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
        <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/treeview/bootstrap-treeview.js?v=1.0.0"></script>
        <script>
            $(function () {
                var closeLayer = function () {
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.closeLayer(index);
                }
                $('.closelayer').click(function (event) {
                    closeLayer();
                });
                $.ajax({
                    url: 'ajax.php',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        ac: 'getCategory'
                    }
                }).done(function (cb) {
                    $('#treeview').treeview({
                        levels: 1,
                        showIcon: false,
                        data: cb,
                        onNodeSelected: function (e, o) {
                            if (o.nodes == null) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'GET',
                                    dataType: 'json',
                                    data: {
                                        ac: 'changeCategory',
                                        cid: o.id,

                                    }
                                }).done(function () {
                                }).fail(function () {
                                });
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
