<?php
require '../../../global.php';
//进入页面后先判断是否有访问权限
inc\Authorize::checkAdminAuthorizeAndJump('aboutUs.edit');
if (isset($_GET['id'])) {
    $banner = $db->get('tb_partners', '*', array('tbid' => $_GET['id']));
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require 'admin/_head.php'; ?>
    <title>合作伙伴管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
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
                <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                <?php if ($_GET['id']) { ?>
                    编辑合作伙伴
                <?php } else { ?>
                    新增合作伙伴
                <?php } ?>
            </h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="editForm" action="partners.ajax.php">
                        <input type="hidden" name="ac" value="edit">
                        <input type="hidden" name="id" value="<?php echo $banner['tbid']; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展示图片</label>
                            <div class="col-sm-10">
                                <p class="form-control-static lookimg">
                                    <?php echo $banner['image'] ? '<img class="img-thumbnail"  style="max-width:326px;max-height:221px;" src="' . SITEROOTURL . $banner['image'] . '"/>' : '【未上传图片】'; ?>
                                </p>
                                <div class="btn-group" id="upload-group1">
                                    <a id="upload1" type="button" class="btn btn-primary btn-xs uploada"
                                       style="position:relative">选择图片文件</a>
                                </div>
                                <input type="hidden" name="image" id="image" value="<?php echo $banner['image']; ?>">
                                <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                            class="imagewh"> 326 x 221</span></div>
                            </div>
                            <span class="help-block m-b-none"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">外链</label>
                            <div class="col-sm-10">
                                <input type="text" name="url" id="url" placeholder="请填写外链"
                                       class="form-control" ignore="ignore" dataType="/^(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?$/" nullmsg="请输入链接" value="<?php echo $banner['url']; ?>">
                                <div class="alert alert-warning">外链格式例如：<span
                                            class="imagewh"> http://www.baidu.com</span></div>
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-10">
                                <input type="text" name="indexid" id="indexid" placeholder="请输入整数排序值(越小越靠前,1最靠前)"
                                       class="form-control" value="<?php echo $banner['indexid']; ?>">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">首页展示</label>
                            <div class="col-sm-10" style="padding-top:7px;">
                                <div class="onoffswitch">
                                    <input id="is_index" name="is_index" class="onoffswitch-checkbox"
                                           type="checkbox" <?php if ($banner['is_index']) { ?> checked="" <?php } ?>>
                                    <label class="onoffswitch-label" for="is_index">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
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
<?php require 'admin/_js.php'; ?>
<script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/validform/Validform_min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/validform/Validform_Datatype.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/webuploader.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table-locale-all.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script>
    $(function () {
        var closeLayer = function () {
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            try {
                parent.closeLayer(index);
            } catch (err) {
                location.href = 'services.list.php';
            }
        }
        $('.closelayer').click(function (event) {
            closeLayer();
        });

        $("#type").change(function (event) {
            if ($(this).val() != 0) {
                $(".ziyuanid").show();
            } else {
                $(".ziyuanid").hide();
            }
        });
        //图片上传
        var uploader1;
        uploader1 = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
            // 文件接收服务端。
            server: 'partners.ajax.php?ac=upload1',
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
                $('#upload-group1').prev('p').html('<img class="img-thumbnail" style="max-width:326px;max-height:221px;" src=" ' + cb.preview + '"/>');
                $('#image').val(cb.url);
            } else {
                alert(cb.state);
            }
            uploader1.removeFile(file);
        });

        var form = $('#editForm').Validform({
            btnSubmit: '#btn-submit',
            postonce: false,
            ignoreHidden: true,
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
</script>
</body>
</html>
