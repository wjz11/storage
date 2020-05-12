<?php
require('../../../global.php');
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>修改头像 - <?php echo getconfig('title'); ?>管理后台</title>
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>修改头像</h5>
            </div>
            <div class="ibox-content">
                <ul class="nav nav-tabs" id="avatar-tab">
                    <li class="active" id="upload"><a href="javascript:;">本地上传</a></li>
                    <li id="webcam"><a href="javascript:;">视频拍照</a></li>
                </ul>
                <div class="m-t m-b">
                    <div>
                        <p id="swfContainer">本组件需要安装Flash Player后才可使用，请从<a href="http://www.adobe.com/go/getflashplayer">这里</a>下载安装。</p>
                    </div>
                    <div id="editorPanelButtons" style="display:none">
                        <p>
                            <a href="javascript:;" class="btn btn-w-m btn-primary button_upload"><i class="fa fa-upload"></i> 上传</a>
                            <a href="javascript:;" class="btn btn-w-m btn-white button_cancel">取消</a>
                        </p>
                    </div>
                    <p id="webcamPanelButton" style="display:none">
                        <a href="javascript:;" class="btn btn-w-m btn-info button_shutter"><i class="fa fa-camera"></i> 拍照</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <!-- fullavatareditor -->
    <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/fullavatareditor/scripts/swfobject.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/fullavatareditor/scripts/fullAvatarEditor.js"></script>
    <script>
    swfobject.addDomLoadEvent(function(){
        var webcamAvailable = false;
        var currentTab = 'upload';
        var callback = function (json) {
    		var id = this.id;
            switch (json.code) {
                case 2:
                    //如果加载原图成功，说明进入了编辑面板，显示保存和取消按钮，隐藏拍照按钮
                    if (json.type == 0) {
    					if(id == "swf")
    					{
    						$('#webcamPanelButton').hide();
    						$('#editorPanelButtons').show();
    					}
                    }
                    //否则会转到上传面板
                    else {
                        //隐藏所有按钮
                        if(id == "swf")$('#editorPanelButtons,#webcamPanelButton').hide();
                    }
                    break;
                case 3:
                    //如果摄像头已准备就绪且用户已允许使用，显示拍照按钮。
                    if (json.type == 0) {
                        if(id == "swf")
    					{
    						$('.button_shutter').removeClass('Disabled');
    						$('#webcamPanelButton').show();
    						webcamAvailable = true;
    					}
                    }
                    else {
    					if(id == "swf")
    					{
    						webcamAvailable = false;
    						$('#webcamPanelButton').hide();
    					}
                        //如果摄像头已准备就绪但用户已拒绝使用。
                        if (json.type == 1) {
                            alert('用户拒绝使用摄像头!');
                        }
                        //如果摄像头已准备就绪但摄像头被占用。
                        else {
                            alert('摄像头被占用!');
                        }
                    }
                    break;
                case 4:
                    alert("您选择的原图片文件大小（" + json.content + "）超出了指定的值(2MB)。");
                    break;
                case 5:
                    //如果上传成功
                    if (json.type == 0) {
    					var html = '<div class="imgList">';
    					for(var i = 0; i < json.content.avatarUrls.length; i++)
    					{
    						html += '<dl><dd><img src="' + json.content.avatarUrls[i] + '" /></dd></dl>';
    					}
                        html += '</div>';
                        swal({
                            title: '头像上传成功',
                            text: html,
                            html: true
                        });
                        console.log(html);
                    }
                    else {
                        alert(json.type);
                    }
                    break;
            }
        };
        var swf = new fullAvatarEditor("<?php echo SITEROOTURL; ?>/admin/js/plugins/fullavatareditor/fullAvatarEditor.swf", "<?php echo SITEROOTURL; ?>/admin/js/plugins/fullavatareditor/expressInstall.swf", "swfContainer", {
            id : 'swf',
            upload_url : 'ajax.php?ac=avatar',	//上传接口
            src_url : '<?php echo file_exists('uploads/admin/'.session('admin_id').'/avatar.source.jpg') ? SITEROOTURL.'uploads/admin/'.session('admin_id').'/avatar.source.jpg' : ''; ?>',
            method : 'post',	//传递到上传接口中的查询参数的提交方式。更改该值时，请注意更改上传接口中的查询参数的接收方式
            src_upload : 1,		//是否上传原图片的选项，有以下值：0-不上传；1-上传；2-显示复选框由用户选择
            tab_visible : false,				//不显示选项卡，外部自定义
            button_visible : false,				//不显示按钮，外部自定义
            avatar_box_border_width : 0,
            avatar_sizes : '200*200',
            avatar_sizes_desc : '200*200像素',
            browse_box_align:'left',            //头像选择框对齐方式
            webcam_box_align:'left',            //头像拍照框对齐方式
            avatar_intro : '     最终会生成下面这个尺寸的头像',
    		avatar_tools_visible:true			//是否显示颜色调整工具
        }, callback);
        //点击上传按钮的事件
        $('.button_upload').click(function () {
            swf.call('upload');
        });
        //点击取消按钮的事件
        $('.button_cancel').click(function () {
            var activedTab = $('#avatar-tab li.active')[0].id;
            swf.call('changepanel', activedTab);
            if (activedTab === 'webcam') {
                $('#editorPanelButtons').hide();
                if (webcamAvailable) {
                    $('.button_shutter').removeClass('Disabled');
                    $('#webcamPanelButton').show();
                }
            }
            else {
                //隐藏所有按钮
                $('#editorPanelButtons,#webcamPanelButton').hide();
            }
        });
        //点击拍照按钮的事件
        $('.button_shutter').click(function () {
            if (!$(this).hasClass('Disabled')) {
                $(this).addClass('Disabled');
                swf.call('pressShutter');
            }
        });
        //选项卡点击事件
        $('#avatar-tab li').click(function () {
            if (currentTab != this.id) {
                currentTab = this.id;
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                if (this.id === 'webcam') {
                    $('#editorPanelButtons').hide();
                    if (webcamAvailable) {
                        $('.button_shutter').removeClass('Disabled');
                        $('#webcamPanelButton').show();
                    }
                }
                else {
                    //隐藏所有按钮
                    $('#editorPanelButtons,#webcamPanelButton').hide();
                }
                swf.call('changepanel', this.id);
            }
        });
    });
    </script>
</body>
</html>
