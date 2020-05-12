<?php
require('../../../global.php');
inc\Authorize::checkAdminAuthorizeAndJump('mannual.edit');

$size_info   = exec("du -sb uploads/ ");
$file_count  = exec("ls -lR uploads/ | grep '-'| wc -l ");
$size        = explode(' ', $size_info)[0];
$use_ratio_0 = (1 - ($size / (1024 * 1024 * 1024 * 2))) * 100;
$use_ratio   = round($use_ratio_0, 2) . '%';
if (isset($_GET['id'])) {
    $info = $db->select('tb_mannual', '*', ['resource_type' => 'goods_category', 'resource_id' => $_GET['id']]);
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
                        编辑<?php
                        $name = $db->get('tb_goods_category', 'name', ['tbid' => $_GET['id']]);
                        echo '「' . $name . '」';
                        ?>资料
                    </h3>
                </div>
                <div class="ibox-content">
                    <div class="row row-lg">
                        <div class="col-sm-12">
                            <form class="form-horizontal" id="editForm" action="../goods/ajax.php">
                                <input type="hidden" name="ac" value="resources_edit">
                                <input type="hidden" name="id1" value="<?php echo $info[0]['tbid']; ?>">
                                <input type="hidden" name="id2" value="<?php echo $info[1]['tbid']; ?>">
                                <input type="hidden" name="resource_type" value="goods_category">
                                <input type="hidden" name="resource_id" value="<?php echo $_GET['id']; ?>">
                                <h4>资料信息</h4>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="width:20%">上传文件总数</td>
                                                <td style="width:30%">
                                                    <div class="input-group">
                                                        <input disabled type="text" class="form-control" value="<?php echo $file_count; ?>">
                                                        <span class="input-group-addon">个</span>
                                                    </div>
                                                </td>
                                                <td style="width:20%">服务器剩余磁盘</td>
                                                <td style="width:30%">
                                                    <div>
                                                        <span>百分比</span>
                                                        <small class="pull-right"><?php echo $use_ratio; ?></small>
                                                    </div>
                                                    <div class="progress progress-small">
                                                        <div style="width:<?php echo $use_ratio; ?>;" class="progress-bar <?php
                                                        if ($use_ratio_0 <= 0.25) {
                                                            echo 'progress-bar-danger';
                                                        }
                                                        ?>"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>资料名</td>
                                                <td><input readonly type="text" name="name" id="name" class="form-control" value="<?php echo $info[0]['name']; ?>"></td>
                                                <td>资料链接</td>
                                                <td><input readonly type="text" name="file" id="file" class="form-control" value="<?php echo $info[0]['file']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>类型</td>
                                                <td><input readonly type="text" name="file_type" id="file_type" class="form-control" value="<?php echo $info[0]['file_type']; ?>"></td>
                                                <td>大小</td>
                                                <td><input readonly type="text" name="size" id="size" class="form-control" value="<?php echo $info[0]['size']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>真实下载</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input disabled type="text" name="count" id="count" class="form-control" value="<?php echo $info[0]['count']; ?>">
                                                        <span class="input-group-addon">次</span>
                                                    </div>
                                                </td>
                                                <td>虚拟下载</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="count_virtual" id="count_virtual" class="form-control" value="<?php echo $info[0]['count_virtual']; ?>">
                                                        <span class="input-group-addon">次</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>创建时间</td>
                                                <td><input readonly type="text" name="createtime" id="createtime" class="form-control" value="<?php echo $info[0]['createtime']; ?>"></td>
                                                <td>编辑时间</td>
                                                <td><input readonly type="text" name="edittime" id="edittime" class="form-control" value="<?php echo $info[0]['edittime']; ?>"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            <?php
                                            echo $info[0]['file'] ?
                                                    '<a href="' . SITEROOTURL . $info[0]['file'] . '" class="btn btn-primary btn-xs" style="position:relative" target="ddd">资料</a>' :
                                                    '【未上传文件】';
                                            ?>
                                        </p>
                                        <div class="btn-group" id="upload-group2">
                                            <a href="javascript:;" id="upload2" class="btn btn-primary btn-xs" style="position:relative">选择文件</a>
                                            <?php    
                                            if(!empty($info[0]['file'])){
                                                    echo '<a class=" btn-info btn-xs" style="margin-left:20px;height:20px" href="' . SITEROOTURL . $info[0]["file"].'" download="'.$info[0]['name'].'">下载资料
                                                        </a>';
                                                    //echo '<a download="' . SITEROOTURL . $info[0]["file"].'">下载资料</a>';
                                                }
                                            ?>
                                        </div>
                                        <div class="alert alert-warning">文件大小不超过2MB</div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="width:20%">上传文件总数</td>
                                                <td style="width:30%">
                                                    <div class="input-group">
                                                        <input disabled type="text" class="form-control" value="<?php echo $file_count; ?>">
                                                        <span class="input-group-addon">个</span>
                                                    </div>
                                                </td>
                                                <td style="width:20%">服务器剩余磁盘</td>
                                                <td style="width:30%">
                                                    <div>
                                                        <span>百分比</span>
                                                        <small class="pull-right"><?php echo $use_ratio; ?></small>
                                                    </div>
                                                    <div class="progress progress-small">
                                                        <div style="width:<?php echo $use_ratio; ?>;" class="progress-bar <?php
                                                        if ($use_ratio_0 <= 0.25) {
                                                            echo 'progress-bar-danger';
                                                        }
                                                        ?>"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>资料名</td>
                                                <td><input readonly type="text" name="name_1" id="name_1" class="form-control" value="<?php echo $info[1]['name']; ?>"></td>
                                                <td>资料链接</td>
                                                <td><input readonly type="text" name="file_1" id="file_1" class="form-control" value="<?php echo $info[1]['file']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>类型</td>
                                                <td><input readonly type="text" name="file_type_1" id="file_type_1" class="form-control" value="<?php echo $info[1]['file_type']; ?>"></td>
                                                <td>大小</td>
                                                <td><input readonly type="text" name="size_1" id="size_1" class="form-control" value="<?php echo $info[1]['size']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>真实下载</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input disabled type="text" name="count_1" id="count_1" class="form-control" value="<?php echo $info[1]['count']; ?>">
                                                        <span class="input-group-addon">次</span>
                                                    </div>
                                                </td>
                                                <td>虚拟下载</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="count_virtual_1" id="count_virtual_1" class="form-control" value="<?php echo $info[1]['count_virtual']; ?>">
                                                        <span class="input-group-addon">次</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>创建时间</td>
                                                <td><input readonly type="text" name="createtime_1" id="createtime_1" class="form-control" value="<?php echo $info[1]['createtime']; ?>"></td>
                                                <td>编辑时间</td>
                                                <td><input readonly type="text" name="edittime_1" id="edittime_1" class="form-control" value="<?php echo $info[1]['edittime']; ?>"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            <?php
                                            echo $info[1]['file'] ?
                                                    '<a href="' . SITEROOTURL . $info[1]['file'] . '" class="btn btn-primary btn-xs" style="position:relative" target="ddd">资料</a>' :
                                                    '【未上传文件】';
                                            ?>
                                        </p>
                                        <div class="btn-group" id="upload-group2_1">
                                            <a href="javascript:;" id="upload2_1" class="btn btn-primary btn-xs" style="position:relative">选择文件</a>
                                            <?php    
                                            if(!empty($info[1]['file'])){
                                                    echo '<a class=" btn-info btn-xs" style="margin-left:20px;height:20px" href="' . SITEROOTURL . $info[1]["file"].'" download="'.$info[1]['name'].'">下载资料
                                                        </a>';
                                                }
                                            ?>
                                            
                                        </div>
                                        <div class="alert alert-warning">文件大小不超过2MB</div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-1">
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
                //--------上传文件----------//
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
                    fileSingleSizeLimit: 2 * 1024 * 1024,
                    accept: {
                        extensions: 'zip,rar,png,jpg,jpeg,gif,docx,doc,xls,xlsx,pdf,ppt,pptx',
                    },
                    compress: false,
                });
                uploader2.on("error", function (type) {
                    if (type == "Q_TYPE_DENIED") {
                        layer.msg("请上传zip、rar、png、jpg、jpeg、gif、docx、doc、xls、xlsx、pdf、ppt、pptx格式文件");
                    } else if (type == "F_EXCEED_SIZE") {
                        layer.msg("文件大小不能超过2M");
                    } else {
                        layer.msg("上传出错！请检查后重新上传！错误代码" + type);
                    }
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
                        $('#upload-group2').prev('p').html('<a href="' + cb.preview + '" class="btn btn-primary btn-xs" style="position:relative">资料</a>');
                        $('#name').val(cb.original);
                        $('#file').val(cb.url);
                        $('#file_type').val(cb.fileType);
                        $('#size').val(cb.size);
                    } else {
                        alert(cb.state);
                    }
                    uploader2.removeFile(file);
                });
                //--------上传文件----------//
                var uploader3 = WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // swf文件路径
                    swf: '<?php echo SITEROOTURL; ?>/member/js/plugins/webuploader/Uploader.swf',
                    // 文件接收服务端。
                    server: 'ajax.php?ac=uploadFile',
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {
                        id: '#upload2_1',
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
                    var $progress2 = $('#progress2_1');
                    //避免重复创建
                    if (!$progress2.length) {
                        $progress2 = $('<a href="javascript:;" id="progress2_1" class="btn btn-white btn-xs disabled"></a>').appendTo('#upload-group2_1');
                    }
                    $progress2.text(percentage * 100 + '%');
                });
                uploader3.on('uploadComplete', function (file) {
                    $('#progress2_1').fadeOut(function () {
                        $(this).remove();
                    });
                });
                uploader3.on('uploadSuccess', function (file, cb) {
                    if (cb.state == 'SUCCESS') {
                        $('#upload-group2_1').prev('p').html('<a href="' + cb.preview + '" class="btn btn-primary btn-xs" style="position:relative" target="ddd">资料</a>');
                        $('#name_1').val(cb.original);
                        $('#file_1').val(cb.url);
                        $('#file_type_1').val(cb.fileType);
                        $('#size_1').val(cb.size);
                    } else {
                        alert(cb.state);
                    }
                    uploader3.removeFile(file);
                });
                //--------上传文件----------//
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
            }
            );
        </script>
    </body>
</html>
