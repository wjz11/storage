<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('banner.category_edit');

if (isset($_GET['id'])) {
    $banner = $db->get('tb_banner_category', '*', array('tbid' => $_GET['id']));
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>Banner管理 - <?php echo getconfig('title'); ?>管理后台</title>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2>
                <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                <?php if ($_GET['id']) { ?>
                    编辑分类
                <?php } else { ?>
                    新增分类
                <?php } ?>
            </h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="editForm" action="category.ajax.php">
                        <input type="hidden" name="ac" value="edit">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" id="title" placeholder="请输入标题" class="form-control"
                                       value="<?php echo $banner['name']; ?>" dataType="*" nullmsg="请输入标题">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">宽度</label>
                            <div class="col-sm-10">
                                <input type="text" name="width" id="width" placeholder="请输入广告位宽度" class="form-control"
                                       value="<?php echo $banner['width']; ?>" dataType="*" nullmsg="请输入广告位宽度">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">高度</label>
                            <div class="col-sm-10">
                                <input type="text" name="height" id="height" placeholder="请输入广告位高度" class="form-control"
                                       value="<?php echo $banner["height"]; ?>" dataType="*" nullmsg="请输入广告位高度">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-lg" type="submit" id="btn-submit">保存内容</button>
                                <button class="btn btn-white btn-lg closelayer" type="button">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('admin/_js.php'); ?>
<script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/validform/Validform_min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/validform/Validform_Datatype.js"></script>
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

        var form = $('#editForm').Validform({
            btnSubmit: '#btn-submit',
            postonce: false,
            showAllError: true,
            //msg：提示Banner;
            //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
            //cssctl:内置的提示Banner样式控制函数，该函数需传入两个参数：显示提示Banner的对象 和 当前提示的状态（既形参o中的type）;
            tiptype: function (msg, o) {
                if (!o.obj.is('form')) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                    var B = o.obj.parents('.form-group');
                    var T = B.find('.help-block');
                    if (o.type == 2) {
                        B.removeClass('has-error').addClass('has-success');
                        T.html('');
                    } else {
                        B.removeClass('has-success').addClass('has-error');
                        T.html("<i class='fa fa-times-circle'></i> " + msg);
                    }
                }
            },
            ajaxPost: true,
            callback: function (data) {
                if (data.status == 'y') {
                    parent.swal('操作成功', '', 'success');
                    closeLayer();
                } else {
                    swal('操作失败', '', 'error');
                }
            }
        });
    });

    function closeLayer(index) {
        layer.close(index);
    }
</script>
</body>
</html>
