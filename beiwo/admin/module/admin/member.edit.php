<?php
use inc\Authorize;

require('../../../global.php');
//进入页面后先判断是否有访问权限
Authorize::checkAdminAuthorizeAndJump('admin.edit');

if(isset($_GET['id'])){
    $admin = $db->get('tb_admin_member', '*', array('tbid' => $_GET['id']));
  
    $authorizelist = $db->get('tb_admin_member','authorizelist',array('tbid'=>$admin['id']));
   
    $parent_lev = $db->get('tb_admin_department','parent_lev',array('tbid'=>$admin['department_id']));
    $department_id = $db->get('tb_admin_department','tbid',array('tbid'=>$parent_lev));
   

}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>人员管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h2>
                    <button type="button" class="btn btn-white btn-sm closelayer">返回</button>
                    <?php if($_GET[id]){?>
                        编辑人员
                    <?php }else{?>
                        新建人员
                    <?php }?>
                </h2>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <form class="form-horizontal" id="editForm" action="member.ajax.php">
                            <input type="hidden" name="ac" value="edit">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <input type="hidden" name="cid" value="<?php echo $_GET['cid']; ?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">帐号</label>
                                <div class="col-sm-10">
                                    <?php if(isset($_GET['id'])){ ?>
                                    <p class="form-control-static"><?php echo $admin['username']; ?></p>
                    				<?php }else{ ?>
                                    <input type="text" name="username" id="username" placeholder="请输入帐号" class="form-control" value="<?php echo $admin['username']; ?>"  dataType="*" nullmsg="请输入帐号">
                    				<?php } ?>
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <?php if($_GET['id'] != session('admin_id')){ ?>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" id="password" placeholder="请输入密码" class="form-control" dataType="*" nullmsg="请输入密码" errormsg="请输入密码" <?php if(isset($_GET['id'])){ ?>ignore="ignore"<?php } ?>>
                                    <span class="help-block m-b-none"><?php if(isset($_GET['id'])){ echo '（如果无需修改则不填）'; } ?></span>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                <label class="col-sm-2 control-label">部门</label>
                                <div class="col-sm-10">
                                    <?php 
                                    $deWhere['AND']['level'] = 0;
                                    
                                    $row = $db->select('tb_admin_department','*', $deWhere); ?>
                                    <select name="parent_lev" id="parent_lev" style="margin-right: 20px;margin-bottom:5px;width:300px" class="form-control text-center" dataType="*" nullmsg="请选择部门">
                                    <option value="">请选择部门</option>
                                    <?php foreach($row as $v){?>
                                        <?php if($department_id == $v['tbid']){?>
                                            <option value="<?php echo $v['tbid']?>" selected><?php echo $v['name']?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo $v['tbid']?>"><?php echo $v['name']?></option>
                                        <?php }?>
                                    <?php }?>
                                    </select>
                                </div>
                                </div>
                                <div class="form-group" >
                                <div class="col-sm-10" style="margin-left: 16.5%;">
                                    <select name="department" id="department" style="margin-right: 20px;margin-bottom:5px;width:300px" class="form-control text-center" dataType="*" nullmsg="请选择职位">
                                        <option value="">请选择职位</option>
                                    </select>
                                    <span class="help-block m-b-none"><?php if(isset($_GET['id'])){ echo '（如果更改职位,则会撤销之前的权限）'; } ?></span>
                                </div>
                                </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">真实姓名</label>
                                <div class="col-sm-10">
                                    <input type="text" name="realname" id="realname" placeholder="请输入真实姓名" class="form-control" dataType="*" nullmsg="请输入真实姓名" errormsg="请输入真实姓名" value="<?php echo $admin['realname']; ?>">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">手机号</label>
                                <div class="col-sm-10">
                                    <input type="text" name="mobile" id="mobile" placeholder="请输入手机号" class="form-control" dataType="/^[1][0-9]{10}$/" nullmsg="请输入手机号" errormsg="手机号格式有误" value="<?php echo $admin['mobile']; ?>">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">工作座机</label>
                                <div class="col-sm-10">
                                    <input type="text" name="special" id="special" placeholder="请输入工作座机" class="form-control"  value="<?php echo $admin['special']; ?>">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">加入时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control text-center" name="applystarttime" id="applystarttime" value="<?php echo $admin['addtime']?>" placeholder="选择加入时间" style="width:300px;" dataType="*" nullmsg="请输入加入时间" errormsg="加入时间格式有误">
                                    <!-- <input type="text" name="createtime" id="createtime" placeholder="请输入时间" class="form-control" dataType="*" nullmsg="请输入时间" errormsg="请输入时间" value="<?php echo $admin['createtime']; ?>"> -->
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">权限</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static" id="authorizeInfo" style="display:none"></p>
                                    <input type="hidden" name="authorizelist" id="authorizelist"  value="<?php echo $admin['authorizelist']; ?>">
                                    <button class="btn btn-white btn-sm" type="button" id="btn-choose">编辑权限</button>
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <input type="hidden" value='<?php echo $authorizelist ?>' id='getdepartment'/>
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
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    <script>
    $(function(){
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            try{
                parent.closeLayer(index);
            }catch(err){
                location.href = 'member.list.php';
            }
        }
        $('#parent_lev').change(function(event){
            getdepartment();
          
        });
        $('#department').change(function(event){
            getpermission();
           
        })
        <?php if(isset($_GET['id'])){?>
            getdepartment();
        <?php }?>
        // getpermission()
        // alert($('#getdepartment').val());
        $('.closelayer').click(function(event){
            try{
                parent.ajaxmemberList();
            }catch(err){

            }
            closeLayer();
        });
        $('#applystarttime').datetimepicker({
            language : 'zh-CN',
            format : 'yyyy-mm-dd hh:ii:ss',
            weekStart : 1,
            startView : 2,
            minView : 2,
            todayHighlight : true,
            autoclose : true
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
                    parent.ajaxmemberList();
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
                // content: 'choose2.php?pauthorizelist=<?php echo $authorizelist ?>&authorizelist='+$('#authorizelist').val(),
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
                url: 'member.ajax.php',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    ac: 'getAuthorizeInfo',
                    authorizelist: $('#authorizelist').val()
                }
            })
            .done(function(cb) {
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
    function getdepartment(){
        department = <?php echo $admin['department_id']?$admin['department_id']:0;?>;
        $.ajax({
            url: 'member.ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                ac: 'getdepartment',
                id: $('#parent_lev').val(),
                department_id: department
            }
        }).done(function(cb) {

            $('#department').html(cb.option);

        });


    }
    function getpermission(){
        $permission = $('#department').val();
        $.ajax({
            url: 'member.ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                ac: 'getpermission',
                id: $('#department').val(),
                // department_id: department
            }
        }).done(function(cb) {
            if(cb.option!=0){
                $('#getdepartment').val(cb.option);
            }


        });
    }
    </script>
</body>
</html>
