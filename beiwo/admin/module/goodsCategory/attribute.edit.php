<?php
require('../../../global.php');
//进入页面后先判断是否有访问权限
checkAdminAuthorizeAndJump('goods_attribute.edit');
if(isset($_GET['id'])){
    $attribute = $db->get('tb_goods_attribute', '*', array('tbid' => $_GET['id']));
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>商品管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h2>
                    <button type="button" class="btn btn-white btn-sm closelayer">返回</button> 
                    <?php if($_GET['id']){ ?>
                        编辑商品属性详情
                    <?php }else{ ?>
                        新增商品属性详情
                    <?php } ?>
                </h2>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <form class="form-horizontal" id="editForm" action="attribute.ajax.php">
                            <input type="hidden" name="ac" value="edit">
                            <input type="hidden" name="id" id="id" value="<?php echo $attribute['tbid'];?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" ajaxurl="attribute.ajax.php?ac=checkname&item=<?php echo $attribute['tbid'];?>" placeholder="请输入名称" class="form-control" value="<?php echo $attribute['name']; ?>" dataType="*" nullmsg="请输入标题">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">属性</label>
                                <div class="col-sm-10">
                                    <div class="row" id="attributeList"></div>
                                    <script id="attributeTemp" type="text/html">
                                        <% if(prop.length != 0){ %>
                                            <% for(var i in prop){ %>
                                                <div class="col-xs-4 col-sm-2 property-item">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="propertyitem[]" value="<%= prop[i] %>" placeholder="可选属性">
                                                        <% if(i!=0){ %>
                                                        <span class="input-group-btn"><button type="button" class="btn btn-default remove-property-btn"><i class="glyphicon glyphicon-remove"></i></button></span>
                                                        <% } %>
                                                    </div>
                                                </div>
                                            <% } %>
                                            <div class="col-xs-4 col-sm-2">
                                                <button type="button" class="btn btn-default btn-block add-property-btn"><i class="glyphicon glyphicon-plus"></i></button>
                                            </div>
                                        <% }else{ %>
                                            <div class="col-xs-4 col-sm-2 property-item">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="propertyitem[]" placeholder="可选属性">
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-2">
                                                <button type="button" class="btn btn-default btn-block add-property-btn"><i class="glyphicon glyphicon-plus"></i></button>
                                            </div>
                                        <% } %>
                                    </script>
                                    <script id="attributeTemps" type="text/html">
                                        <div class="col-xs-4 col-sm-2 property-item">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="propertyitem[]" value="" placeholder="可选属性"><span class="input-group-btn"><button type="button" class="btn btn-default remove-property-btn"><i class="glyphicon glyphicon-remove"></i></button></span>
                                            </div>
                                        </div>
                                    </script>
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
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/arttemplate/template.js"></script>
    <script>
    $(function(){
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            try{
                parent.closeLayer(index);
            }catch(err){
                location.href = 'suggest.list.php';
            }
        }
        $('.closelayer').click(function(event){
            closeLayer();
        });
        //数据复原
        ajaxattributeList();
        
        $('body').on('click', '.add-property-btn', function(event) {
            event.preventDefault();
            $(this).parent().before(template('attributeTemps'));
            $(this).parents('.properties-item').find('.remove-property-btn').prop('disabled', false);
        }).on('click', '.remove-property-btn', function(event) {
            event.preventDefault();
            $properties = $(this).parents('.properties-item');
            $(this).parents('.property-item').remove();
            if($properties.find('.remove-property-btn').length == 1){
                $properties.find('.remove-property-btn').prop('disabled', true);
            }
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
    function ajaxattributeList(){
        	console.log(111)
        $.ajax({
            url: 'attribute.ajax.php',
            type: 'GET',
            dataType: 'json',
            data: {
                ac: 'getProperty',
                id: $('input[name="id"]').val()
            }
        }).done(function(prop) {
            $('#attributeList').html(template('attributeTemp', {'prop': prop}));
        });
    }
    </script>
</body>
</html>
