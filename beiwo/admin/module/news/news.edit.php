<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('news.edit');

$news_category = $db->select('tb_news_category', '*');
if(isset($_GET['id'])){
    $news = $db->get('tb_news', '*', array('tbid' => $_GET['id']));

    $_GET['cid'] = $news['news_category_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>新闻管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
     <link href="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
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
                    <?php if($_GET['id']){ ?>
                        编辑内容
                    <?php }else{ ?>
                        新增内容
                    <?php } ?>
                </h2>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <form class="form-horizontal" id="editForm" action="news.ajax.php">
                            <input type="hidden" name="ac" value="edit">
                            <input type="hidden" name="news_category_id" value="<?php echo $_GET['cid'];?>">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>标题</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" placeholder="请输入标题" class="form-control" value="<?php echo $news['name']; ?>" dataType="*" nullmsg="请输入标题">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>作者</label>
                                <div class="col-sm-10">
                                    <input type="text" name="author" id="author" placeholder="请输入作者" class="form-control" value="<?php echo $news['author']; ?>" dataType="*" nullmsg="请输入作者">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>简介</label>
                                <div class="col-sm-10">
                                    <textarea type="text" name="intro" id="intro" placeholder="请输入简介"
                                          class="form-control" style="min-height: 100px"><?php echo $news['intro']; ?></textarea>
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <!-- <?php if (2 == $_GET['cid']) {?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">开始时间</label>
                                <div class="col-sm-3">
                                    <input type="text" name="begintime" id="begintime" placeholder="请输入优惠券领取开始时间" class="form-control" value="<?php echo $news['begintime']; ?>" dataType="*" nullmsg="请输入开始时间">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">结束时间</label>
                                <div class="col-sm-3">
                                    <input type="text" name="endtime" id="endtime" placeholder="请输入优惠券领取结束时间" class="form-control" value="<?php echo $news['endtime']; ?>" dataType="*" nullmsg="请输入结束时间">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <?php } ?> -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>展示图片</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static lookimg"><?php echo $news['image'] ? '<img class="img-thumbnail" style="max-width:630px;max-height:300px;" src="'.SITEROOTURL.$news['image'].'"  />' : '【未上传图片】' ?></p>
                                    <div class="btn-group" id="upload-group">
                                        <a id="upload" type="button" class="btn btn-primary btn-xs uploada" style="position:relative">选择图片文件</a>
                                    </div>
                                    <input type="hidden" name="image" id="image" value="<?php echo $news['image']; ?>" dataType="*">
                                    <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                                class="imagewh"> 630 x 300</span></div>
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>详细内容</label>
                                <div class="col-sm-10">
                                	<script type="text/plain" id="editor" style="height:300px;"><?php echo $news['content']; ?></script>
									<input type="hidden" name="content" id="content">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>是否置顶</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline i-checks"><input type="radio" name="is_top" value="1" <?php if($news['is_top'] == 1 || !isset($news)){ ?>checked<?php } ?>>是</label>
                                    <label class="radio-inline i-checks"><input type="radio" name="is_top" value="0" <?php if($news['is_top'] == 0){ ?>checked<?php } ?>>否</label>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>是否PC官网首页推荐</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline i-checks"><input type="radio" name="is_index" value="1" <?php if($news['is_index'] == 1 || !isset($news)){ ?>checked<?php } ?>>是</label>
                                    <label class="radio-inline i-checks"><input type="radio" name="is_index" value="0" <?php if($news['is_index'] == 0){ ?>checked<?php } ?>>否</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color:red">*</span>排序id</label>
                                <div class="col-sm-10">
                                    <input type="text" name="indexid" id="indexid" placeholder="请输入排序id" class="form-control" value="<?php echo $news['indexid']; ?>" dataType="*" nullmsg="请输入排序id">
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
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table-locale-all.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.config.js"></script>
	<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.all.js"></script>
        <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    <script>
    $(function(){
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            try{
                parent.closeLayer(index);
            }catch(err){
                location.href = 'news.list.php';
            }
        }
        $('.closelayer').click(function(event){
            closeLayer();
        });
        $('#begintime, #endtime').datetimepicker({
            language : 'zh-CN',
            format : 'yyyy-mm-dd',
            weekStart : 1,
            startView : 2,
            minView : 2,
            todayHighlight : true,
            autoclose : true
        }).on('changeDate', function(){
            form.check(false, $(this));
        });
        $('#begintime').on('changeDate', function(event) {
            $('#endtime').datetimepicker('setStartDate', event.date);
        });
        $('#endtime').on('changeDate', function(event) {
            $('#begintime').datetimepicker('setEndDate', event.date);
        });
        //var ue = UE.getEditor('editor');
        var ue = UE.getEditor('editor', {
            toolbars: [
                [
                    'fullscreen', 'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'fontborder',  '|', 'forecolor', 'insertorderedlist', 'insertunorderedlist', 'cleardoc', 'removeformat', '|',
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
            server: 'news.ajax.php?ac=upload',
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
                $('#upload-group').prev('p').html('<img class="img-thumbnail" style="max-width:630px;max-height:300px;"  src="'+ cb.preview + '"  />');
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
    		//msg：提示Banner;
    		//o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
    		//cssctl:内置的提示Banner样式控制函数，该函数需传入两个参数：显示提示Banner的对象 和 当前提示的状态（既形参o中的type）;
    		tiptype: function(msg, o){
    			if(!o.obj.is('form')){//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
    				var B = o.obj.parents('.form-group');
    				var T = B.find('.help-block');
    				if(o.type == 2){
    					B.removeClass('has-error').addClass('has-success');
    					T.html('');
    				}else{
    					B.removeClass('has-success').addClass('has-error');
    					T.html("<i class='fa fa-times-circle'></i> " + msg);
    				}
    			}
    		},
    		ajaxPost: true,
            beforeSubmit: function(){
                $('#content').val(ue.getContent());
                return true;
            },
    		callback: function(data){
				if(data.status == 'y'){
                    parent.swal('操作成功', '', 'success');
                    closeLayer();
				}else{
                    swal('操作失败', '', 'error');
				}
    		}
    	});
        $("body").on("click",".img-thumbnail",function(){
            layer.open({
              type: 1,
              title: false,
              closeBtn: 0,
              area: ['750px','300px'],
              skin: 'layui-layer-nobg', //没有背景色
              shadeClose: true,
              content: '<img src="'+$(this).attr("src")+'" />'
            });
        });
    });
    </script>
</body>
</html>
