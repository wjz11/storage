<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
inc\Authorize::checkAdminAuthorizeAndJump('aboutUs.edit');
$row_category = $db->get('tb_about_us_category', '*', array("tbid"=>1));
$row = $db->get('tb_about_us', '*',array("tbid"=>1));
//print_r("<pre>");
//print_r($row);
//die;
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>平台设置 - <?php echo getconfig('title'); ?>管理后台</title>
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
            关于倍沃
            </h2>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="editForm" action="ajax.php">
                        <input type="hidden" name="ac" value="edit">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">企业名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" placeholder="请输入名称" class="form-control"
                                       value="<?php echo $row_category['name']; ?>" dataType="*" nullmsg="请输入名称"
                                       errormsg="请输入名称">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">英文名</label>
                            <div class="col-sm-10">
                                <input type="text" name="english_name" id="english_name" placeholder="请输入英文名"
                                       class="form-control" value="<?php echo $row_category['english_name']; ?>" dataType="*"
                                       nullmsg="请输入英文名" errormsg="请输入英文名">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展示图片</label>
                            <div class="col-sm-10">
                                <p class="form-control-static lookimg">
                                    <?php echo $row_category['image'] ? '<img class="img-thumbnail" style="max-width:490px;max-height:360px;" src="' . SITEROOTURL . $row_category['image'] . '"  />' : '【未上传图片】' ?>
                                </p>
                                <div class="btn-group" id="upload-group">
                                    <a id="upload" type="button" class="btn btn-primary btn-xs uploada"
                                       style="position:relative">选择图片文件</a>
                                </div>
                                <input type="hidden" name="image" id="image" value="<?php echo $row_category['image']; ?>">
                                <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                            class="imagewh"> 490 x 360</span></div>
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label class="col-sm-2 control-label">企业介绍</label>
                            <div class="col-sm-10">
                                <textarea type="text" name="intro" id="intro" placeholder="请输入简介" class="form-control" style="min-height:100px"
                                          dataType="*" nullmsg="请输入简介"
                                          errormsg="请输入简介"><?php echo $row['intro']; ?></textarea>
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">成立年份</label>
                            <div class="col-sm-10">
                                <input type="text" name="set_up_time" id="set_up_time" placeholder="请输入成立年份"
                                       class="form-control" value="<?php echo $row['set_up_time']; ?>" dataType="*"
                                       nullmsg="请输入成立年份" errormsg="请输入成立年份">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">产品种类</label>
                            <div class="col-sm-10">
                                <input type="text" name="pro_count" id="pro_count" placeholder="请输入产品种类"
                                       class="form-control" value="<?php echo $row['pro_count']; ?>" dataType="*"
                                       nullmsg="请输入产品种类" errormsg="请输入产品种类">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">销售省份</label>
                            <div class="col-sm-10">
                                <input type="text" name="provinces_count" id="provinces_count" placeholder="请输入销售省份"
                                       class="form-control" value="<?php echo $row['provinces_count']; ?>" dataType="*"
                                       nullmsg="请输入销售省份" errormsg="请输入销售省份">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">年销售额（亿）</label>
                            <div class="col-sm-10">
                                <input type="text" name="year_count" id="year_count" placeholder="请输入年销售额（亿）"
                                       class="form-control" value="<?php echo $row['year_count']; ?>" dataType="*"
                                       nullmsg="请输入年销售额（亿）" errormsg="请输入年销售额（亿）">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">发明专利数</label>
                            <div class="col-sm-10">
                                <input type="text" name="patent_for_invention" id="patent_for_invention" placeholder="请输入发明专利数"
                                       class="form-control" value="<?php echo $row['patent_for_invention']; ?>" dataType="*"
                                       nullmsg="请输入发明专利数" errormsg="请输入发明专利数">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">实用新型专利</label>
                            <div class="col-sm-10">
                                <input type="text" name="new_utility_patents" id="new_utility_patents" placeholder="请输入实用新型专利"
                                       class="form-control" value="<?php echo $row['new_utility_patents']; ?>" dataType="*"
                                       nullmsg="请输入实用新型专利" errormsg="请输入实用新型专利">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">国家标注</label>
                            <div class="col-sm-10">
                                <input type="text" name="national_standard" id="national_standard" placeholder="请输入国家标注"
                                       class="form-control" value="<?php echo $row['national_standard']; ?>" dataType="*"
                                       nullmsg="请输入国家标注" errormsg="请输入国家标注">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">行业技术</label>
                            <div class="col-sm-10">
                                <input type="text" name="Industry_technology" id="Industry_technology" placeholder="请输入行业技术"
                                       class="form-control" value="<?php echo $row['Industry_technology']; ?>" dataType="*"
                                       nullmsg="请输入行业技术" errormsg="请输入行业技术">
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label">倍沃历程</label>
                            <div class="col-sm-10">
                                <p class="form-control-static lookimg"><?php echo $row['course'] ? '<img class="img-thumbnail" style="max-width:1200px;max-height:683px;" src="'.SITEROOTURL.$row['course'].'"  />' : '【未上传图片】' ?></p>
                                <div class="btn-group" id="upload-group">
                                    <a id="upload" type="button" class="btn btn-primary btn-xs course" style="position:relative">选择图片文件</a>
                                </div>
                                <input type="hidden" name="course" id="image" value="<?php echo $row['course']; ?>">
                                <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                            class="imagewh"> 1200 x 683</span></div>
                                <span class="help-block m-b-none"></span>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">企业文化</label>
                            <div class="col-sm-10">
                                <textarea type="text" name="content_category" id="content_category" placeholder="请输入企业文化"
                                          class="form-control" style="min-height: 100px" dataType="*" nullmsg="请输入企业文化"
                                          errormsg="请输入企业文化"><?php echo $row_category['content']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">详细介绍</label>
                            <div class="col-sm-10">
                                <script type="text/plain" id="editor_intro" style="height:300px;"><?php echo $row['content']; ?></script>
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
