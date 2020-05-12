<!-- 全局js -->
<script src="<?php echo SITEROOTURL; ?>/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/skin/lbuil.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/bootstrap.min.js?v=3.3.5"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/layer/layer.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/iCheck/icheck.min.js"></script>
<script>
$(function(){
    $('.wrapper').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
        $(this).removeClass('animated');
    });
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});
</script>
