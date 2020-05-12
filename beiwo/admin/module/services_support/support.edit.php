<?php
require '../../../global.php';
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('services_support.support_edit');
if (isset($_GET['id'])) {
    $banner = $db->get('tb_server_text', '*', array('tbid' => $_GET['id']));
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require 'admin/_head.php'; ?>
    <title>技术资料管理 - <?php echo getconfig('title'); ?>管理后台</title>
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
                <button type="button" class="btn btn-white btn-sm closelayer">返回</button>
                <?php if ($_GET['id']) { ?>
                    编辑资料
                <?php } else { ?>
                    新增资料
                <?php } ?>
            </h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="editForm" action="support.ajax.php">
                        <input type="hidden" name="ac" value="edit">
                        <input type="hidden" name="id" value="<?php echo $banner['tbid']; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:red">*</span>名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" id="title" placeholder="请输入名称" class="form-control" value="<?php echo $banner['title']; ?>" dataType="*" nullmsg="请输入名称">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group"  id="typediv">
                            <label class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="type" name="type" dataType="*" nullmsg="请选择类型">
                                    
                                    <option <?php if ($banner['url_type']==2 || $banner['url_type']==1) echo "style='display:none;'";?> value="">请选择类型</option>
                                    <option value="<?php echo 1; ?>" <?php
                                    if ($banner['url_type'] == 1)
                                    {
                                        ?>selected<?php } ?>>链接</option>
                                    <option value="<?php echo 2; ?>" <?php
                                    if ($banner['url_type'] == 2)
                                    {
                                        ?>selected<?php } ?>>文件</option>
                                    
                                </select>
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group lj" <?php 
                                if($banner['url_type']!=1){
                                    echo 'style="display:none"';
                                }?>>
                            <label class="col-sm-2 control-label"><span style="color:red">*</span>链接地址</label>
                            <div class="col-sm-10">
                                <input type="text"  name="url" id="url" placeholder="请输入链接地址" class="form-control" value="<?php echo $banner['url']; ?>">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <!-- <div class="form-group zy"<?php 
                                if($banner['url_type']!=2){
                                    echo 'style="display:none"';
                                }?>>
                            <label class="col-sm-2 control-label"><span style="color:red">*</span>&nbsp;文&nbsp;&nbsp;件</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <?php
                                    echo $banner['file'] ?
                                            '<a href="' . SITEROOTURL . $banner['file'] . '" class="btn btn-primary btn-xs" style="position:relative" target="ddd">资料</a>' :
                                            '【未上传文件】';
                                    ?>
                                </p>
                                <div class="btn-group" id="upload-group2_1">
                                    <a href="javascript:;" id="upload2_1" class="btn btn-primary btn-xs" style="position:relative">选择文件</a>
                                    <?php    
                                    if(!empty($banner['file'])){
                                        echo '<a class=" btn-banner btn-xs" style="margin-left:20px;height:20px" href="' . SITEROOTURL . $banner["file"].'" download="'.$banner['title'].'">下载资料
                                            </a>';
                                    }
                                    ?>
                                    
                                </div>
                                <div class="alert alert-warning">文件大小不超过2MB</div>
                                <span class="help-block m-b-none"></span>
                                <input type="hidden" name="file_name" id="file_name"  value="<?php if(isset($banner["file"])) echo $banner['file'] ;?>"/>
                            </div>
                        </div> -->
                        <div class="form-group zy">
                            
                            <label class="col-sm-2 control-label">上传文件</label>
                            <div class="col-sm-10">
                                <p class="form-control-static lookimg">
                                    <?php
                                    echo $banner['file'] ?
                                            '<a href="' . SITEROOTURL . $banner['file'] . '" class="btn btn-primary btn-xs" style="position:relative" target="ddd">资料</a>' :
                                            '【未上传文件】';
                                    ?>
                                </p>
                                <div class="btn-group" id="upload-group1">
                                    <a id="upload1" type="button" class="btn btn-primary btn-xs uploada"
                                       style="position:relative">选择文件</a>
                                       <?php    
                                    if(!empty($banner['file'])){
                                        echo '<a class=" btn-banner btn-xs" style="margin-left:20px;height:20px" href="' . SITEROOTURL . $banner["file"].'" download="'.$banner['title'].'">下载资料
                                            </a>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" name="file_name" id="file_name"  value="<?php if(isset($banner["file"])) echo $banner['file'] ;?>"/>
                                <div class="alert alert-warning">大小不超过2MB<span
                                        class="imagewh"> </span></div>
                            </div>
                            <span class="help-block m-b-none"></span>
                        </div>
                        <div class="form-group" >
                            <label class="col-sm-2 control-label">下载次数</label>
                            <div class="col-sm-2">
                                <input type="text" readonly class="form-control" value="<?php echo $banner['count']; ?>">
                                <span class="help-block m-b-none"></span>
                            </div>
                            <label class="col-sm-1 control-label">类型</label>
                            <div class="col-sm-2">
                                <input type="text" name="file_type" readonly id="file_type" class="form-control" value="<?php echo $banner['file_type']; ?>">
                                <span class="help-block m-b-none"></span>
                            </div>
                            <label class="col-sm-1 control-label">大小</label>
                            <div class="col-sm-2">
                                <input type="text" name="size" readonly id="size" class="form-control" value="<?php echo $banner['size']; ?>">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:red">*</span>排序</label>
                            <div class="col-sm-10">
                                <input type="text" dataType="n" name="indexid" id="indexid" placeholder="请输入整数排序值(越小越靠前,1最靠前)"
                                       class="form-control" value="<?php echo $banner['indexid']; ?>">
                                <span class="help-block m-b-none"></span>
                                <div class="alert alert-warning">注：排序根据填写的数字正序展示，数字越小越靠前展示</div>
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
                location.href = 'support.list.php';
            }
        }
        $('.closelayer').click(function (event) {
            closeLayer();
        });

        $("#type").change(function (event) {
            if ($(this).val() == 1) {
                $(".lj").show();
                $(".zy").hide();
            } else {
                $(".lj").hide();
                $(".zy").show();
            }
        });

        //图片上传
        // var uploader1;
        // uploader1 = WebUploader.create({
        //     // 选完文件后，是否自动上传。
        //     auto: true,
        //     // swf文件路径
        //     swf: '<?php echo SITEROOTURL; ?>/admin/js/plugins/webuploader/Uploader.swf',
        //     // 文件接收服务端。
        //     server: 'support.ajax.php?ac=uploadFile',
        //     // 选择文件的按钮。可选。
        //     // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        //     pick: {
        //         id: '#upload1',
        //         multiple: false
        //     },
        //     fileSingleSizeLimit: 2 * 1024 * 1024,
        //     accept: {
        //         extensions: 'zip,rar,png,jpg,jpeg,gif,docx,doc,xls,xlsx,pdf,ppt,pptx',
        //     },
        //     // accept: {
        //     //     title: 'Images',
        //     //     extensions: 'gif,jpg,jpeg,bmp,png',
        //     //     mimeTypes: 'image/*'
        //     // },
        //     compress: false,
        // });
        // uploader1.on('uploadProgress', function (file, percentage) {
        //     var $progress1 = $('#progress1');
        //     //避免重复创建
        //     if (!$progress1.length) {
        //         $progress1 = $('<a href="javascript:;" id="progress1" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group1');
        //     }
        //     $progress1.text(percentage * 100 + '%');
        // });
        // uploader1.on('uploadComplete', function (file) {
        //     $('#progress1').fadeOut(function () {
        //         $(this).remove();
        //     });
        // });
        // uploader1.on('uploadSuccess', function (file, cb) {
        //     if (cb.state == 'SUCCESS') {
        //         $('#upload-group1').prev('p').html('<img class="img-thumbnail" style="max-width:480px;max-height:205px;" src=" ' + cb.preview + '" width="480" height="205" />');
        //         $('#image').val(cb.url);
        //     } else {
        //         alert(cb.state);
        //     }
        //     uploader1.removeFile(file);
        // });
        //--------上传文件----------//
        var uploader3 = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?php echo SITEROOTURL; ?>/member/js/plugins/webuploader/Uploader.swf',
            // 文件接收服务端。
            server: 'support.ajax.php?ac=uploadFile',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id: '#upload1',
                multiple: false
            },
            fileSingleSizeLimit: 2 * 1024 * 1024,
            accept: {
                extensions: 'zip,rar,png,jpg,jpeg,gif,docx,doc,xls,xlsx,pdf,ppt,pptx',
            },
            compress: false,
        });
        uploader3.on("error", function (type) {
            if (type == "Q_TYPE_DENIED") {
                layer.msg("请上传zip、rar、png、jpg、jpeg、gif、docx、doc、xls、xlsx、pdf、ppt、pptx格式文件");
            } else if (type == "F_EXCEED_SIZE") {
                layer.msg("文件大小不能超过2M");
            } else {
                layer.msg("上传出错！请检查后重新上传！错误代码" + type);
            }
        });
        uploader3.on('uploadProgress', function (file, percentage) {
            var $progress2 = $('#progress1');
            //避免重复创建
            if (!$progress2.length) {
                $progress2 = $('<a href="javascript:;" id="progress1" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group1');
            }
            $progress2.text(percentage * 100 + '%');
        });
        uploader3.on('uploadComplete', function (file) {
            $('#progress1').fadeOut(function () {
                $(this).remove();
            });
        });
        uploader3.on('uploadSuccess', function (file, cb) {
            if (cb.state == 'SUCCESS') {
                $('#upload-group1').prev('p').html('<a href="' + cb.preview + '" class="btn btn-primary btn-xs" style="position:relative" target="ddd">资料</a>');
                $('#file_name').val(cb.url);
                // $('#name_1').val(cb.original);
                // $('#file_1').val(cb.url);
                $('#file_type').val(cb.fileType);
                $('#size').val(cb.size);
            } else {
                alert(cb.state);
            }
            uploader3.removeFile(file);
        });
        //--------上传文件----------//
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
            beforeCheck: function(){
                var type = $("#type").val();
                if(type==1){
                    var url = $("#url").val();
                    if(!url){
                        layer.msg("链接地址不能为空");
                        return false;
                    }
                    reg=/(http|https):\/\/([\w.]+\/?)\S*/;     
                    if(!reg.test(url)){    
                        layer.msg("请加上前缀http://或https://");
                        return false;   
                    }
                }else{
                    var file_name = $('#file_name').val();
                    if(!file_name){
                        layer.msg("请上传资料");
                        return false;   
                    }
                }
                // $('#content').val(ue.getContent());
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
