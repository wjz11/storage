<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
inc\Authorize::checkAdminAuthorizeAndJump('goodsCategory.order');
$order_list = $db->select('tb_order_list(o)', [
    '[>]tb_goods(g)' => ['o.good_id' => 'tbid'],
    '[>]tb_goods_category(c)' => ['o.good_category_id' => 'tbid']
        ], [
    'o.tbid',
    'g.name(gname)',
    'c.name(cname)',
        ], [
    'AND' => ['o.isdel' => 0],
    'ORDER' => ['o.indexid' => 'DESC'],
        ]);
if ($order_list && is_array($order_list))
{
    foreach ($order_list as $value)
    {
        $tmp = [];
        $tmp['tbid'] = $value['tbid'];
        $tmp['title'] = $value['gname'] ? $value['gname'] : $value['cname'];
        $list[] = $tmp;
    }
}
else
{
    $list = [];
}
//print_r("<pre>");
//print_r($list);
//die;
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('admin/_head.php'); ?>
        <title>排序管理 - <?php echo getconfig('title'); ?>管理后台</title>
        <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    </head>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>排序管理</h3>
                </div>
                <div class="col-sm-12">
                    <div class="row row-lg">
                        <div class="ibox-content">
                            <ul class="sortable-list connectList agile-list">
                                <?php if($list && is_array($list)){
                                    foreach($list as $val){
                                    ?>
                                <li class="success-element" value="<?php echo $val['tbid']; ?>"><?php echo $val['title'];?></li>
                                    <?php }
                                    } ?>
                                <!--<li class="success-element">加强过程管理，及时统计教育经费使用情况，做到底码清楚</li>-->
                                <!--<li class="success-element">支持财会人员的继续培训工作。</li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require('admin/_js.php'); ?>
        <script src="<?php echo SITEROOTURL; ?>/admin/js/jquery-ui-1.10.4.min.js"></script>
        <!-- 自定义js -->
        <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
        <script>
            $(document).ready(function () {
                $(".sortable-list").sortable({
                    connectWith: ".connectList"
                }).disableSelection();
            });
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
                        content: 'edit.php?id=' + id
                    });
                }).on('click', '.remove', function (event) {
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
                }).on('click', '.status', function (event) {
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
                }).on('click', '.item', function (event) {
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
                }).on('click', '.goods', function (event) {
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
                    layer.open({
                        type: 2,
                        title: false,
                        closeBtn: false,
                        area: ['100%', '100%'],
                        shade: false,
                        move: false,
                        content: 'edit.php?cid=<?php echo $_GET['cid']; ?>'
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
