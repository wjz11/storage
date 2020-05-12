<?php

use \inc\Authorize;

require('../../../global.php');
Authorize::checkAdminAuthorizeAndJump('mannual.edit');
if (!empty($_GET['id'])) {
    $row = $db->get('tb_mannual_category', '*', ['tbid' => $_GET['id']]);
}
?>
<!DOCTYPE html>
<html>
<head>
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
    <?php require('admin/_head.php'); ?>
    <title>部门管理 - <?php echo getconfig('title'); ?>管理后台</title>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h3>
                <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                联系我们分类
            </h3>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="editForm" action="ajax.php">
                        <input type="hidden" name="ac" value="editCategory">
                        <input type="hidden" name="id" value="<?php echo $row['tbid']; ?>">


                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" placeholder="请输入名称" class="form-control"
                                       value="<?php echo $row['name']; ?>" dataType="*" nullmsg="请输入名称"
                                       errormsg="请输入名称">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">英文名</label>
                            <div class="col-sm-10">
                                <input type="text" name="english_name" id="english_name" placeholder="请输入英文名"
                                       class="form-control" value="<?php echo $row['english_name']; ?>" dataType="*"
                                       nullmsg="请输入英文名" errormsg="请输入英文名">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展示图片</label>
                            <div class="col-sm-10">
                                <p class="form-control-static lookimg"><?php echo $row['image'] ? '<img class="img-thumbnail" style="max-width:800px;max-height:215px;" src="' . SITEROOTURL . $row['image'] . '"  />' : '【未上传图片】' ?></p>
                                <div class="btn-group" id="upload-group">
                                    <a id="upload" type="button" class="btn btn-primary btn-xs upload"
                                       style="position:relative">选择图片文件</a>
                                </div>
                                <input type="hidden" name="image" id="image" value="<?php echo $row['image']; ?>">
                                <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                            class="imagewh"> 800 x 215</span></div>
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">logo</label>
                            <div class="col-sm-10">
                                <p class="form-control-static lookimg"><?php echo $row['logo'] ? '<img class="img-thumbnail" style="max-width:86px;max-height:86px;"  src="' . SITEROOTURL . $row['logo'] . '"  />' : '【未上传图片】' ?></p>
                                <div class="btn-group1" id="upload-group1">
                                    <a id="upload1" type="button" class="btn btn-primary btn-xs upload"
                                       style="position:relative">选择图片文件</a>
                                </div>
                                <input type="hidden" name="logo" id="logo" value="<?php echo $row['logo']; ?>">
                                <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                            class="imagewh"> 86 x 86</span></div>
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
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/arttemplate/template.js"></script>
<script>
    $(function () {
        var closeLayer = function () {
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.closeLayer(index);
        }
        $('.closelayer').click(function (event) {
            closeLayer();
        });
        //主图1
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
            // 文件接收服务端。
            server: 'ajax.php?ac=upload',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id: '#upload',
                multiple: false
            },
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            compress: false,
        });
        uploader.on('uploadProgress', function (file, percentage) {
            var $progress = $('#progress');
            //避免重复创建
            if (!$progress.length) {
                $progress = $('<a href="javascript:;" id="progress" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group');
            }
            $progress.text(percentage * 100 + '%');
        });
        uploader.on('uploadComplete', function (file) {
            $('#progress').fadeOut(function () {
                $(this).remove();
            });
        });
        uploader.on('uploadSuccess', function (file, cb) {
            if (cb.state == 'SUCCESS') {
                $('#upload-group').prev('p').html('<img class="img-thumbnail" style="max-width:800px;max-height:215px;" src="' + cb.preview + '"  />');
                $('#image').val(cb.url);
            } else {
                alert(cb.state);
            }
            uploader.removeFile(file);
        });
        //主图1
        var uploader1 = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
            // 文件接收服务端。
            server: 'ajax.php?ac=upload',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id: '#upload1',
                multiple: false
            },
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            compress: false,
        });
        uploader1.on('uploadProgress', function (file, percentage) {
            var $progress = $('#progress');
            //避免重复创建
            if (!$progress.length) {
                $progress = $('<a href="javascript:;" id="progress" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group');
            }
            $progress.text(percentage * 100 + '%');
        });
        uploader1.on('uploadComplete', function (file) {
            $('#progress').fadeOut(function () {
                $(this).remove();
            });
        });
        uploader1.on('uploadSuccess', function (file, cb) {
            if (cb.state == 'SUCCESS') {
                $('#upload-group1').prev('p').html('<img class="img-thumbnail" style="max-width:86px;max-height:86px;"  src="' + cb.preview + '"  />');
                $('#logo').val(cb.url);
            } else {
                alert(cb.state);
            }
            uploader.removeFile(file);
        });
        var form = $('#editForm').Validform({
            btnSubmit: '#btn-submit',
            postonce: false,
            showAllError: true,
            //msg：提示信息;
            //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
            //cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
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
</script>
</body>
</html>
