<?php
require('../../../global.php');
inc\Authorize::checkAdminAuthorizeAndJump('goodsCategory.edit');

$row = $db->get('tb_goods_category_info', '*',array("tbid"=>1));
//$_GET['cid'] = $row['category_id'];

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
                    <h3>
                        <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                        编辑首页产品分类信息
                    </h3>
                </div>
                <div class="ibox-content">
                    <div class="row row-lg">
                        <div class="col-sm-12">
                            <form class="form-horizontal" id="editForm" action="ajax.php">
                                <input type="hidden" name="ac" value="add_edit">
                                <input type="hidden" name="id" value="<?php echo 1; ?>">
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">名&nbsp;&nbsp;称</span>
                                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $row['name']; ?>" dataType="*">
                                        </div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">副&nbsp;名&nbsp;称</span>
                                            <input type="text" name="name_deputy" id="name_deputy" class="form-control" value="<?php echo $row['name_deputy']; ?>" dataType="*">
                                        </div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">英&nbsp;&nbsp;文</span>
                                            <input type="text" name="name_en" id="name_en" class="form-control" value="<?php echo $row['name_en']; ?>">
                                        </div>
                                    </div>
                                </div>
         
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"><span style="color:red">*</span>&nbsp;简&nbsp;&nbsp;介</label>
                                    <div class="col-sm-11">
                                        <textarea type="text" name="intro" id="intro" class="form-control" style="min-height: 100px" dataType="*"><?php echo $row['intro']; ?></textarea>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-1 control-label">展示图片</label>
                                    <div class="col-sm-11">
                                        <p class="form-control-static lookimg">
                                            <?php echo $row['image'] ? '<img class="img-thumbnail" style="max-width:690px;max-height:690px;" src="' . SITEROOTURL . $row['image'] . '"  />' : '【未上传图片】' ?>
                                        </p>
                                        <div class="btn-group" id="upload-group"><a id="upload" type="button" class="btn btn-primary btn-xs uploada" style="position:relative">选择图片文件</a></div>
                                        <div class="btn-group"><a href="javascript:;" class="btn btn-danger btn-outline btn-xs gooddel" style="position:relative">删除</a></div>
                                        <input type="hidden" name="image" id="image" value="<?php echo $row['image']; ?>" >
                                        <div class="alert alert-warning">
                                            图片大小不超过2MB，图片尺寸最小比例
                                            <span class="imagewh"> 690 x 690</span>
                                        </div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>


                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-1">
                                        <button class="btn btn-primary btn-lg" type="submit" id="btn-submit">保存内容</button>
                                        <button class="btn btn-white btn-lg closelayer" type="button">取&nbsp;&nbsp;消</button>
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
        <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.config.js"></script>
        <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.all.js"></script>
        <script>
            $(function () {
                var closeLayer = function () {
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.closeLayer(index);
                }
                $('.closelayer').click(function (event) {
                    closeLayer();
                });
                
                //图片上传
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
                        $('#upload-group').prev('p').html('<img class="img-thumbnail" style="max-width:690px;max-height:690px;" src=" ' + cb.preview + '"/>');
                        $('#image').val(cb.url);
                    } else {
                        alert(cb.state);
                    }
                    uploader.removeFile(file);
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
                        id: '#upload2',
                        multiple: false
                    },
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,bmp,png',
                        mimeTypes: 'image/*'
                    },
                    compress: false,
                });
                uploader.on('uploadProgress', function(file, percentage){
                    var $progress = $('#progress2');
                    //避免重复创建
                    if(!$progress.length){
                        $progress = $('<a href="javascript:;" id="progress2" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group2');
                    }
                    $progress.text(percentage * 100 + '%');
                });
                uploader.on('uploadComplete', function(file){
                    $('#progress2').fadeOut(function(){
                        $(this).remove();
                    });
                });
                uploader.on('uploadSuccess', function(file, cb){
                    if(cb.state == 'SUCCESS'){
                        $('#upload-group2').prev('p').html('<img class="img-thumbnail" src=" '+ cb.preview + '" />');
                        $('#images').val(cb.url);
                    }else{
                        alert(cb.state);
                    }
                    uploader.removeFile(file);
                });

                //主图2
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
                uploader1.on('uploadProgress', function(file, percentage){
                    var $progress1 = $('#progress1');
                    //避免重复创建
                    if(!$progress1.length){
                        $progress1 = $('<a href="javascript:;" id="progress1" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group1');
                    }
                    $progress1.text(percentage * 100 + '%');
                });
                uploader1.on('uploadComplete', function(file){
                    $('#progress1').fadeOut(function(){
                        $(this).remove();
                    });
                });
                uploader1.on('uploadSuccess', function(file, cb){
                    if(cb.state == 'SUCCESS'){
                        $('#upload-group1').prev('p').html('<img class="img-thumbnail" src=" '+ cb.preview + '" />');
                        $('#images1').val(cb.url);
                    }else{
                        alert(cb.state);
                    }
                    uploader1.removeFile(file);
                });
                //删除图片
                $(".gooddel").click(function () {
                    $(this).parent().next('input').val('');
                    $(this).parent().prevAll('.form-control-static').html('【未上传图片】');
                });
               
                //表单提交
                var form = $('#editForm').Validform({
                    btnSubmit: '#btn-submit',
                    postonce: false,
                    showAllError: true,
                    //msg：提示信息;
                    //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
                    //cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
                    tiptype: function (msg, o) {
                        if (!o.obj.is('form')) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                            var B = $(o.obj.parents('.form-group')[0]);
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
