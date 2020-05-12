<?php
use \inc\Authorize;
require('../../../global.php');
Authorize::checkAdminAuthorizeAndJump('aboutUs.edit');
if (!empty($_GET['id'])) {
    $row = $db->get('tb_about_us_item', '*', ['tbid' => $_GET['id']]);
    $_GET['cid'] = $row['category_id'];
}

$categoryName = $db->get('tb_about_us_category', 'name', ['tbid' => $_GET['cid']]);
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>部门管理 - <?php echo getconfig('title'); ?>管理后台</title>
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>
                    <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                   编辑分项
                </h3>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <form class="form-horizontal" id="editForm" action="ajax.php">
                            <input type="hidden" name="ac" value="editItem">
                            <input type="hidden" name="id" value="<?php echo $row['tbid']; ?>">
                            <input type="hidden" name="category_id" value="<?php echo $_GET['cid']; ?>">
                            
                           <div class="form-group">
                                <label class="col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                    <script type="text/plain" id="editor_content" style="height:300px;"><?php echo $row['content']; ?></script>
                                    <input type="hidden" name="content" id="content">
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
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/arttemplate/template.js"></script>
      <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.config.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/ueditor1_4_3_2/ueditor.all.js"></script>
    <script>
    $(function(){
         var ue = UE.getEditor('editor_content', {
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
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.closeLayer(index);
        }
        $('.closelayer').click(function(event){
            closeLayer();
        });

        var form = $('#editForm').Validform({
            btnSubmit: '#btn-submit',
            postonce: false,
            showAllError: true,
            //msg：提示信息;
            //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
            //cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
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
    });
    </script>
</body>
</html>
