<?php
require('../../../global.php');
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>属性选择管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link rel="stylesheet" href="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <style media="screen">
        .panel-body{
            min-height: 418px;
        }
    </style>
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="tabs-container">
            <div class="tabs-right">
                <div class="tab-content form-horizontal">
                    <div class="col-sm-12">
                        <div id="toolbar" class="btn-group">
                            <input type="hidden" name="attribute_id" id="attribute_id" value="<?php echo $_GET['attritem'];?>">
                        </div>
                        <table id="table" data-toggle="table" data-url="ajax.php?ac=chooseattr&item=<?php echo $_GET['attritem'];?>" data-classes="table table-hover table-condensed" data-striped="true" data-pagination="true" data-side-pagination="server" data-page-list="[10, 20]" data-search="true" data-show-refresh="true" data-toolbar="#toolbar">
                            <thead>
                                <tr>
                                    <th data-field="name" data-halign="center" data-align="center" data-width="81">名称</th>
                                    <th data-field="do" data-halign="center" data-align="center" data-width="150">操作</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table-locale-all.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script>
    $(function(){
        $('[name="attribute[]"]').bootstrapSwitch();
        $('#authorizeNavtabs a:first').tab('show');
        $('#table').on('click', 'input[name="attribute[]"]', function(){
            var gthis=$(this);
            var id = $(this).val();
            var attribute_id=$("#attribute_id").val();
                var referrarr=[];
                var new_arr=[]
                if(attribute_id!=""){
                    referrarr=attribute_id.split(",");
                }else{
                    referrarr=[];
                }
                if($(this).is(':checked')) {
                    referrarr.push(gthis.val());
                    for(var i=0;i<referrarr.length;i++) {
                    　　var items=referrarr[i];
                    　　//判断元素是否存在于new_arr中，如果不存在则插入到new_arr的最后
                    　　if($.inArray(items,new_arr)==-1) {
                    　　　　new_arr.push(items);
                    　　}
                    }
                    referrarr=new_arr;
                    var attribute_idstr=referrarr.join(',');
                    $("#attribute_id").val(attribute_idstr);
                }else{
                    referrarr.splice(jQuery.inArray($(this).val(),referrarr),1);
                    var attribute_idstr=referrarr.join(',');
                    $("#attribute_id").val(attribute_idstr);
                }
        });
    });
    function getAuthorize(){
        return $("#attribute_id").val();
    }
    </script>
</body>
</html>
