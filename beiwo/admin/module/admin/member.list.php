<?php
use inc\Authorize;

require('../../../global.php');
//进入页面后先判断是否有访问权限
Authorize::checkAdminAuthorizeAndJump('admin.browse');
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>员工管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link href="<?php echo SITEROOTURL; ?>/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <!-- 页面上涉及到需要权限判断的数据统一存放在body下起始位置 -->
    <!-- 而对应需要权限的操作模块，通过「authorize-xxxx」类名做标记 -->
    <!-- 最终通过js进行统一隐藏/显示，或销毁DOM -->
    <input type="hidden" id="authorize-edit" value="<?php echo Authorize::checkAdminAuthorize('admin.browse') ? 1 : 0; ?>">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="col-sm-4 pull-right input-group">
                    <input type="text" name="search" class="form-control" placeholder="账号 / 姓名 ">
                    <span class="input-group-btn">
                        <button id="search_btn" class="btn btn-primary" type="button"><i class="fa fa-search"></i> 搜索</button>
                    </span>
                </div>
                <h2>员工管理</h2>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-3">
                        <select id="parent_lev" name="parent_lev" class="form-control text-center" style="margin-top:10px">
                            <option value="">全部部门</option>
                            <?php
                                $deWhere['AND']['level'] = 0;
                                
                               
                                $parent_lev = $db->select('tb_admin_department', '*', $deWhere);
                                foreach($parent_lev as $v){
                                    echo '<option value="'.$v['tbid'].'">'.$v['name'].'</option>';
                                }
                            ?>
                        </select>
                        <select name="department" id="department" style="margin-top: 10px;margin-bottom:10px" class="form-control text-center"></select>
                    </div>
                    <div class="col-sm-6 col-sm-offset-3 m-b-sm">
                        <button class="pull-right btn btn-success" type="button" style="width:150px" id="add"><i class="fa fa-user-plus"></i> 创建账户</button>
                    </div>
                </div>
                <div class="row row-lg" id="memberList"></div>
                <script id="memberTemp" type="text/html">
                    <% if(rows.length != 0){ %>
                        <% for(var i in rows){ %>
                        <div class="col-sm-6 col-md-4">
                            <div class="contact-box" style="background:#fafafa">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <img alt="image" class="img-circle m-t-xs img-responsive" src="<%= rows[i].avatar %>" style="width:120px;height: 120;">
                                            <div class="m-t-xs font-bold"><%= rows[i].username %></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
<!--                                 if(authorize ){           -->
                                        <% if(authorize){ %>
                                        <div class="btn-group pull-right">
                                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle" aria-expanded="false">操作 <span class="caret"></span></button>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="javascript:;" class="edit" data-id="<%= rows[i].tbid %>"><i class="fa fa-fw fa-edit"></i> 编辑</a></li>
                                                <li role="presentation" access-control="usr.edit" access-control-do="remove" class="divider"></li>
                                                 <% if(authorize && admin_id != rows[i].tbid ){ %>
                                                <li><a href="javascript:;" class="remove" data-id="<%= rows[i].tbid %>"><i class="fa fa-fw fa-trash"></i> 删除</a></li>
                                                  <% } %>
                                            </ul>
                                        </div>
                                        <% } %>
                                        <address class="m-t" style="line-height:24px">
                                            <i class="fa fa-fw fa-user"></i> <strong><%= rows[i].realname %></strong><br>
                                            <i class="fa fa-fw fa-group"></i> <%= rows[i].department  %><br>
                                            <i class="fa fa-fw fa-phone"></i> <%= rows[i].adminmobile %><br>
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <small><abbr title="最近一次登录时间"><i class="fa fa-clock-o"></i></abbr> <%= rows[i].logintime %></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <% } %>
                    <% }else{ %>
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="jumbotron text-center text-danger">
                                <h2><i class="fa fa-warning"></i> 没有搜索到任何员工</h2>
                            </div>
                        </div>
                    <% } %>
                </script>
            </div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <!-- 自定义js -->
    <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
    <script>
    //此处为权限操作专属区域，禁止写其它业务逻辑代码
    $(function(){
        if($('#authorize-edit').val() == 0){
            $('.authorize-edit').hide();
        }
    });
    </script>
    <!-- Bootstrap table -->
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/bootstrap-table-locale-all.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/arttemplate/template.js"></script>
    <script>
    $(function(){
        ajaxmemberList();
        getdepartment();
        $('#parent_lev').change(function(event){
            // alert(1);
            getlist();
            getdepartment();
        });
        $('#department').change(function(event){
            getpermission();
        })
        $('#memberList').on('click', '.remove', function(event){
            event.preventDefault();
            var id = $(this).data().id;
            swal({
                title: "您确定要删除该员工吗",
                text: "删除后将无法恢复，请谨慎操作！",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "是的，我要删除！",
                cancelButtonText: "我再考虑一下…",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function(){
                $.ajax({
                    url: 'member.ajax.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        ac: 'delete',
                        id: id
                    }
                }).done(function(){
                    swal("删除成功！", "", "success");
                    ajaxmemberList();
                }).fail(function(){
                    swal("删除失败！", "请重新操作！", "error");
                });
            });
        })
        .on('click', '.edit', function(event){
            event.preventDefault();
            var id = $(this).data().id;
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'member.edit.php?id='+id
            });
        });
        $('#add').click(function(event){
            event.preventDefault();
            layer.open({
                type: 2,
                title: false,
                closeBtn: false,
                area: ['100%', '100%'],
                shade: false,
                move: false,
                content: 'member.edit.php'
            });
        });
        $('#search_btn').click(function(event) {
            ajaxmemberList();
        });
        $('[name="department"]').change(function(event) {
            ajaxmemberList();
        });
    });
    function closeLayer(index){
        layer.close(index);
    }
    function ajaxmemberList() {
        var department = $('#department').val();
        if($('#parent_lev').val() ==""){
            department = "";
        }
        $.ajax({
            url: 'member.ajax.php',
            type: 'GET',
            dataType: 'json',
            data: {
                ac: 'listMember',
                search: $('[name="search"]').val(),
                department: department,
                parent_lev: $('#parent_lev').val(),
            }
        }).done(function(cb) {
            $('#memberList').html(template('memberTemp', cb));
        });
    }
        function getlist() {
        $.ajax({
            url: 'member.ajax.php',
            type: 'GET',
            dataType: 'json',
            data: {
                ac: 'listMember',
                search: $('[name="search"]').val(),
                parent_lev: $('#parent_lev').val(),
            }
        }).done(function(cb) {
            $('#memberList').html(template('memberTemp', cb));
        });
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
