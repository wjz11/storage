<?php
require('../../../global.php');

$authorizelist = explode(',', $_GET['authorizelist']);
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title>人员管理 - <?php echo getconfig('title'); ?>管理后台</title>
    <link rel="stylesheet" href="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
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
                <ul class="nav nav-tabs" id="authorizeNavtabs" style="margin-left:0">
                    <?php
                    foreach($g_admin_authorize as $k => $v){
                        echo '<li>';
                            echo '<a data-toggle="tab" href="#tab-'.$k.'">'.$v['_title'].'</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
                <div class="tab-content form-horizontal">
                    <?php
                    foreach($g_admin_authorize as $k => $v){
                        echo '<div id="tab-' . $k . '" class="tab-pane">';
                        echo '<div class="panel-body">';
                        echo '<h3>' . $v['_title'] . '</h3>';
                        $i = 0;
                        $count = count($g_admin_authorize[$k]);
                        foreach ($g_admin_authorize[$k] as $kk => $vv) {
                            if ($i != 0) {
                                if ($i % 2 == 1) {
                                    echo '<div class="hr-line-dashed"></div>';
                                    echo '<div class="row" style="height:30px">';
                                }
                                echo '<div class="col-xs-6">';
                                echo '<label class="col-xs-6 control-label">' . $vv . '</label>';
                                $ischecked = '';
                                if (in_array($k . '.' . $kk, $authorizelist)) {
                                    $ischecked = 'checked';
                                }
                                echo '<div class="col-xs-6 switch"><input type="checkbox" name="authorize[]" value="' . $k . '.' . $kk . '" data-size="small" data-on-text="开启" data-off-text="关闭" ' . $ischecked . ' /></div>';
                                echo '</div>';
                                if ($i % 2 == 0 || $i + 1 == $count) {
                                    echo '</div>';
                                }
                            }
                            $i++;
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require('admin/_js.php'); ?>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/content.js?v=1.0.0"></script>
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script>
    $(function(){
        $('[name="authorize[]"]').bootstrapSwitch();
        $('#authorizeNavtabs a:first').tab('show');
    });
    function getAuthorize(){
        var authorize = [];
        $('[name="authorize[]"]:checked').each(function(index, el) {
            authorize.push($(this).val());
        });
        return authorize.join(',');
    }
    </script>
</body>
</html>
