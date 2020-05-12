<?php
use inc\Authorize;

require('../../../global.php');
Authorize::checkAdminAuthorizeAndJump('department.edit');
$parent_lev = $db->get('tb_admin_department', '*', array('tbid' => $_GET['parent_lev']));
if(isset($_GET['id'])){
    $set = $db->get('tb_admin_department','*',array('tbid'=>$_GET['id']));
}
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
                <h2>
                    <button type="button" class="btn btn-white btn-sm closelayer">返回</button>
                    编辑部门
                </h2>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <form class="form-horizontal" id="editForm" action="department.ajax.php">
                            <input type="hidden" name="ac" value="editJob">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <input type="hidden" name="parent_lev" value="<?php echo $_GET['parent_lev'];?>" />
                            <div class="form-group">
                                <label class="col-sm-2 control-label">部门</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?php echo $parent_lev['name']; ?></p>
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="main" id="main" placeholder="请输入部门主管" class="form-control" value="<?php echo $set['name']; ?>" dataType="*" nullmsg="请输入部门主管" errormsg="请输入部门主管">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">简介</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control val-note" id="intro" name="intro" rows="5" style="resize:none" placeholder="简介" ignore="ignore" dataType="*1-250" nullmsg="请输入简介" errormsg="最多250个字"><?php echo $set['intro'];?></textarea>
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
<!--                            <div class="hr-line-dashed"></div>-->
<!--                            <div class="form-group">-->
<!--                                <label class="col-sm-2 control-label">权限</label>-->
<!--                                <div class="col-sm-10">-->
<!--                                    <p class="form-control-static" id="authorizeInfo" style="display:none"></p>-->
<!--                                    <input type="hidden" name="authorizelist" id="authorizelist" dataType="*" nullmsg="还没有选择任何权限，请编辑权限" value="--><?php //echo $set['authorizelist']; ?><!--">-->
<!--                                    <button class="btn btn-white btn-sm" type="button" id="btn-choose">编辑权限</button>-->
<!--                                    <span class="help-block m-b-none"></span>-->
<!--                                </div>-->
<!--                            </div>-->


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
    <script>
    $(function(){
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            try{
                parent.closeLayer(index);
            }catch(err){
                location.href = 'department.list.php';
            }
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
    		callback: function(data){
				if(data.status == 'y'){
                    parent.swal('操作成功', '', 'success');
                    closeLayer();
                }else{
                    swal('操作失败', '', 'error');
                }
    		}
    	});
        $('#btn-choose').click(function(event) {
            layer.open({
                type: 2,
                title: '编辑权限',
                area: ['800px', '90%'],
                content: 'choose.php?authorizelist='+$('#authorizelist').val(),
                btn: ['确认', '取消'],
                yes: function(index, layero){
                    $('#authorizelist').val(window.frames[$(layero).find('iframe').attr('name')].getAuthorize());
                    getAuthorizeInfo();
                    layer.close(index);
                },
                btn2: function(index){
                    layer.close(index);
                }
            });
        });
        getAuthorizeInfo();
    });
    function getAuthorizeInfo(){
        if($('#authorizelist').val() != ''){
            $.ajax({
                url: 'department.ajax.php',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    ac: 'getAuthorizeInfo',
                    authorizelist: $('#authorizelist').val()
                }
            }).done(function(cb) {
                $('#authorizeInfo').show();
                $('#authorizeInfo span').remove();
                $(cb).each(function(index, el) {
                    $('#authorizeInfo').append('<span class="label" style="line-height:2.2">'+this+'</span> ');
                });
            });
        }else{
            $('#authorizeInfo span').remove().hide();
        }
    }
    </script>
</body>
</html>
