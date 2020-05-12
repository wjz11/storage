<?php
require('../../../global.php');
inc\Authorize::checkAdminAuthorizeAndJump('mannual.edit');
if (isset($_GET['id'])) {
    $info        = $db->get('tb_mannual', '*', array('tbid' => $_GET['id']));
    $_GET['cid'] = $info['category_id'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('admin/_head.php'); ?>
        <title>分类管理 - <?php echo getconfig('title'); ?>管理后台</title>
        <style media="screen">
            .webuploader-container {
                position: relative;
            }
            .webuploader-element-invisible {
                position: absolute !important;
                clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                clip: rect(1px,1px,1px,1px);
            }
            .webuploader-pick {
            }
            .webuploader-pick-hover {
            }
            .webuploader-pick-disable {
                opacity: 0.6;
                pointer-events:none;
            }
        </style>
    </head>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>
                        <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                        编辑说明书
                    </h3>
                </div>
                <div class="ibox-content">
                    <div class="row row-lg">
                        <div class="col-sm-12">
                            <form class="form-horizontal" id="editForm" action="ajax.php">
                                <input type="hidden" name="ac" value="edit">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <input type="hidden" name="category_id" value="<?php echo $_GET['cid']; ?>">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">标题</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" id="name" placeholder="请输入标题" class="form-control" value="<?php echo $info['name']; ?>" dataType="*" nullmsg="请输入标题">
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">下载文件</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $info['file'] ? '<img class="img-thumbnail" src="../../img/file.jpg" style="width:100px;"/>' : '【未上传文件】' ?></p>
                                        <div class="btn-group" id="upload-group2">
                                            <a href="javascript:;" id="upload2" class="btn btn-primary btn-xs" style="position:relative">选择文件</a>
                                        </div>
                                        <input type="hidden" name="file" id="file" value="<?php echo $info['file']; ?>">
                                        <input type="hidden" name="size" id="size" value="<?php echo $info['size']; ?>">
                                        <input type="hidden" name="file_type" id="file_type" value="<?php echo $info['file_type']; ?>">
                                        <div class="alert alert-warning">文件大小不超过20MB</div>
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

                //--------上传文件----------
                var uploader2 = WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // swf文件路径
                    swf: '<?php echo SITEROOTURL; ?>/member/js/plugins/webuploader/Uploader.swf',
                    // 文件接收服务端。
                    server: 'ajax.php?ac=uploadFile',
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {
                        id: '#upload2',
                        multiple: false
                    },
                    /*accept: {
                     title: 'RAR, EXCEl, WORD, PDF',
                     extensions: 'rar,zip, doc, pdf, xls, docx, xlsx',
                     mimeTypes: ''
                     extensions: 'rar,zip, doc, pdf, xls, docx, xlsx',
                     mimeTypes: 'Video/*'

                     },*/

                    compress: false,
                });
                uploader2.on('uploadProgress', function (file, percentage) {
                    var $progress2 = $('#progress2');
                    //避免重复创建
                    if (!$progress2.length) {
                        $progress2 = $('<a href="javascript:;" id="progress2" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group2');
                    }
                    $progress2.text(percentage * 100 + '%');
                });
                uploader2.on('uploadComplete', function (file) {
                    $('#progress2').fadeOut(function () {
                        $(this).remove();
                    });
                });
                uploader2.on('uploadSuccess', function (file, cb) {
                    if (cb.state == 'SUCCESS') {
                        $('#upload-group2').prev('p').html('<img class="img-thumbnail" src="../../img/file.jpg" style="width:100px;" />');
                        $('#file').val(cb.url);
                        $('#size').val(cb.size);
                        $('#file_type').val(cb.fileType);
                    } else {
                        alert(cb.state);
                    }
                    uploader2.removeFile(file);
                });
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
                        $('#upload-group').prev('p').html('<img class="img-thumbnail" src=" ' + cb.preview + '"/>');
                        $('#image').val(cb.url);
                    } else {
                        alert(cb.state);
                    }
                    uploader.removeFile(file);
                });

                //展示图
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
                    var $progress1 = $('#progress1');
                    //避免重复创建
                    if (!$progress1.length) {
                        $progress1 = $('<a href="javascript:;" id="progress1" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group1');
                    }
                    $progress1.text(percentage * 100 + '%');
                });
                uploader1.on('uploadComplete', function (file) {
                    $('#progress1').fadeOut(function () {
                        $(this).remove();
                    });
                });
                uploader1.on('uploadSuccess', function (file, cb) {
                    if (cb.state == 'SUCCESS') {
                        $('#upload-group1').prev('p').html('<img class="img-thumbnail" src=" ' + cb.preview + '" width="118" height="118" />');
                        $('#images1').val(cb.url);
                    } else {
                        alert(cb.state);
                    }
                    uploader1.removeFile(file);
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
                    beforeCheck: function () {
                        var re = true;
                        var property = [];
                        var propertyFlag = true;
                        $('.properties-item').each(function (index, el) {
                            var this_property = $(this).find('input[name^="property"]').val();
                            if (property.indexOf(this_property) == -1) {
                                property.push(this_property);
                            } else {
                                propertyFlag = false;
                            }
                        });
                        if (!propertyFlag) {
                            re = false;
                            alert('SKU属性名称有重复，请检查！');
                        }
                        return re;
                    },
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
