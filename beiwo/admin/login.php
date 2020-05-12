<?php
require('../global.php');
?>
<!DOCTYPE html>
<html>
<head>
<?php require('admin/_head.php'); ?>
<title>登录 - <?php echo getconfig('title'); ?>管理后台</title>
<script>if(window.top !== window.self){window.top.location = window.location;}</script>
</head>

<body class="gray-bg">
    <div class="absolute-center">
        <div class="middle-box text-center loginscreen animated fadeInDown" style="width:450px;clear:both">
            <form class="m-t" id="loginForm" action="login.ajax.php">
                <input id="value_ip" name="value_ip" type="hidden" value="" />
                <input id="ac" name="ac" type="hidden" value="login" />
                <input id="value_ipnm" name="value_ipnm" type="hidden" value="" />
                <input id="hiddenps" name="hiddenps"  type="hidden" />
            <div>
                <div>
            		 <div class="reldiv" style="width:100%">
                         <div class="absdiv ipinfo" style="top:-5px;left:0px"></div>
                         <div class="absdiv logo"></div>
                     </div>
                     <h1 class="logo-tl">&nbsp; </h1>
                </div>
                <div class="form-group">
                    <input type="text" id="username" name="username" value="" class="form-control" placeholder="用户名" datatype="*" nullmsg="请输入用户名！">
                </div>
                <div class="form-group">
                    <input id="password" name="password" type="password" class="form-control" placeholder="密码" datatype="*" nullmsg="请输入密码！">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger block full-width m-b login-button loginbtn ladda-button" data-id="loginbtn" data-style="zoom-out" data-size="l" id="btn-submit">登 录</button>
            	</div>
            </div>
            </form>
        </div>
    </div>
    <div style="position:absolute;bottom:5px;width:100%;height:30px;clear:both">
        <div class="center-block" style="width:570px">
            <div class="pull-right">感谢使用&nbsp;&nbsp;<a href="http://www.1one.cn" target="_blank" class="nounderline">商企通（LOOKBI）</a>&nbsp;&nbsp;进行创作。浙江易网科技股份有限公司开发 | 官方文档 | 反馈</a></div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <script src="js/sha1.js"></script>
    <script src="js/plugins/validform/Validform_min.js"></script>
    <script src="js/plugins/validform/Validform_Datatype.js"></script>
    <script>
    $.include(['skin/function.js']);
    $.include(['skin/supersized.css','skin/supersized.3.2.7.min.js','skin/supersized-init.js']);
    $.include(['skin/style.css']);
    $.include(['skin/common.css']);
    $.include(['http://pv.sohu.com/cityjson?ie=utf-8']);
    $(function(){
      document.onkeydown = function(e){ 
        var ev = document.all?window.event:e;
        if(ev.keyCode==13){
            $('#btn-submit').submit();
         }
       }
        if(returnCitySN!=undefined){
             $("#value_ip").val(returnCitySN["cip"]);$("#value_ipnm").val(returnCitySN["cname"]);
                $('.ipinfo').html('<i class="fa fa-map-marker"></i> '+returnCitySN["cname"]+'('+returnCitySN["cip"]+')');
        }
        $(".logo").css('background-image','url(skin/logo_2.png)').everyTime(5000,function(){AddPerAnimateEffect($(".logo"),'flipInX');$(".logo").css('background-image','url(skin/logo_'+parseInt(6*Math.random())+'.png)');});

        var form = $('#loginForm').Validform({
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
    				}else{
    					B.removeClass('has-success').addClass('has-error');
                        layer.tips(msg, o.obj, {
                          tips: [4, '#66AFE9']
                        });
    				}
    			}
    		},
    		beforeCheck:function(curform){
    			//在表单提交执行验证之前执行的函数，curform参数是当前表单对象。
    			//这里明确return false的话将不会继续执行验证操作;	
   			 	/* $('#hiddenps').val(hex_sha1($('#password').val())); */
    			$('#hiddenps').val($('#password').val());
      			$('#password').val();
    		},
    		ajaxPost: true,
    		callback: function(data){
				if(data.status == 'y'){
                    location.href = 'index.php';
				}else{
                    swal({
                        title: "登录失败",
                        text: "提示信息：" + data.info,
                        type: "error"
                    }, function(){
                   	 $('#password').val();
                    });
				}
    		}
    	});
    });
    </script>
</body>
</html>
