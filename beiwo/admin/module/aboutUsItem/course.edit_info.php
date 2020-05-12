<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
inc\Authorize::checkAdminAuthorizeAndJump('aboutUs.edit');

$row = $db->get('tb_about_us', '*',array("tbid"=>1));

?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>倍沃历程 - <?php echo getconfig('title'); ?>管理后台</title>
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
            <h2>
                <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
            倍沃历程信息编辑
            </h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="editForm" action="ajax.php">
                        <input type="hidden" name="ac" value="edit_info">
                        <input type="hidden" name="id" value="<?php echo $row['tbid'];?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">倍沃历程</label>
                            <div class="col-sm-10">
                                <script type="text/plain" id="editor_intro" style="height:300px;"><?php echo $row['course']; ?></script>
                                <input type="hidden" name="content" id="content">
                                    <span class="help-block m-b-none"></span>
                                    </div>
                                    </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-lg" type="button" id="btn-submit">保存内容</button>
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
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.config.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.all.js"></script>
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
        var ue = UE.getEditor('editor_intro', {
            toolbars: [
                [
                    'fullscreen', 'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'fontborder', '|', 'forecolor', 'insertorderedlist', 'insertunorderedlist', 'cleardoc', 'removeformat', '|',
                    'lineheight', '|',
                    'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'unlink', '|',
                    'simpleupload', 'insertimage', '|',
                    'inserttable', 'deletetable',
                ]
            ],
            autoHeightEnabled: true,
            autoFloatEnabled: true
        });

        //主图1
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
            // 文件接收服务端。
            server: 'about_us.ajax.php?ac=upload',
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
        uploader.on('uploadProgress', function(file, percentage){
            var $progress = $('#progress');
            //避免重复创建
            if(!$progress.length){
                $progress = $('<a href="javascript:;" id="progress" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group');
            }
            $progress.text(percentage * 100 + '%');
        });
        uploader.on('uploadComplete', function(file){
            $('#progress').fadeOut(function(){
                $(this).remove();
            });
        });
        uploader.on('uploadSuccess', function(file, cb){
            if(cb.state == 'SUCCESS'){
                $('#upload-group').prev('p').html('<img class="img-thumbnail" style="max-width:1200px;max-height:683px;" src="'+ cb.preview + '"  />');
                $('#image').val(cb.url);
            }else{
                alert(cb.state);
            }
            uploader.removeFile(file);
        });

        var form = $('#editForm').Validform({
            btnSubmit: '#btn-submit',
            postonce: false,
            showAllError: true,
            //msg：提示新闻;
            //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
            //cssctl:内置的提示新闻样式控制函数，该函数需传入两个参数：显示提示新闻的对象 和 当前提示的状态（既形参o中的type）;
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
            beforeSubmit: function () {
                $('#content').val(ue.getContent());

                return true;
            },
            callback: function (data) {
                if (data.status == 'y') {
                    swal('操作成功', '', 'success');
                } else {
                    swal('操作失败', '', 'error');
                }
            }
        });
    });
</script>
</body>
</html>
