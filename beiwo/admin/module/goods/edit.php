<?php
require('../../../global.php');

if (isset($_GET['id'])) {
    $goods   = $db->get('tb_goods', '*', array('tbid' => $_GET['id']));
    $cid     = $goods['goods_category_id'];
    $goodimg = explode("<{|}>", $goods['image']);
}
if (isset($_GET['parent_category_id'])) {
    $cid = $_GET['parent_category_id'];
}
$categoryname = getAllCategoryNameByCid($cid);

function getAllCategoryNameByCid($cid) {
    global $db;
    $category = $db->get('tb_goods_category', '*', array('tbid' => $cid));
    $arr[]    = $category['name'];
    if ($category['category_id'] != 0) {
        $arr = array_merge(getAllCategoryNameByCid($category['category_id']), $arr);
    }
    return $arr;
}

$data_info = '1234567';
$info_list = $db->select('tb_goods_sku', '*', ['goods_id' => $_GET['id'], 'isdel[!]' => 1]);
if ($info_list && is_array($info_list)) {
    foreach ($info_list as $value) {
        $tmp           = [];
        $tmp['field0'] = $value['tbid'];
        $tmp['field1'] = $value['article_number'];
        $tmp['field2'] = $value['specifications'];
        $tmp['field3'] = $value['list_price'] / 100;
        $tmp['field4'] = $value['promotion_price'] / 100;
        $tmp_list[]    = $tmp;
    }
    $data_info = json_encode($tmp_list, true);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('admin/_head.php'); ?>
        <title>商品管理 - <?php echo getconfig('title'); ?>商家管理后台</title>
        <link href="<?php echo SITEROOTURL; ?>admin/js/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo SITEROOTURL; ?>admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo SITEROOTURL; ?>admin/css/plugins/webuploader/webuploader.css">
        <link href="<?php echo SITEROOTURL; ?>admin/css/demo/webuploader-demo.css" rel="stylesheet">
        <style media="screen">
            .webuploader-container {
                position: relative;
            }
            .webuploader-element-invisible {
                position: absolute !important;
                clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                clip: rect(1px,1px,1px,1px);
            }
            .webuploader-pick {
            }
            .webuploader-pick-hover {
            }
            .webuploader-pick-disable {
                opacity: 0.6;
                pointer-events:none;
            }
        </style>
    </head>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>
                        <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                        编辑商品
                    </h3>
                </div>
                <div class="ibox-content">
                    <div class="row row-lg">
                        <div class="col-sm-12">
                            <form class="form-horizontal" id="editForm" action="ajax.php">
                                <input type="hidden" name="ac" value="edit">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">类&nbsp;目</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo implode(' - ', $categoryname); ?></p>
                                        <input type="hidden" name="goods_category_id" value="<?php echo $cid; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">名&nbsp;&nbsp;称</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon">中&nbsp;文</span>
                                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $goods['name']; ?>">
                                        </div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon">英&nbsp;文</span>
                                            <input type="text" name="english_name" id="english_name" class="form-control" value="<?php echo $goods['english_name']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">简&nbsp;介</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" name="intro" id="intro" class="form-control" style="min-height: 100px"><?php echo $goods['intro']; ?></textarea>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">货号规格</label>
                                    <div class="col-sm-10">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>货号</th>
                                                    <th>规格</th>
                                                    <th>目录价</th>
                                                    <th>促销价</th>
                                                    <th>操作</th>
                                                </tr>
                                            </thead>
                                            <tbody class="input-group-add">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                <label class="col-sm-2 control-label">是否促销</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline i-checks"><input type="radio" name="iscuxiao" value="1" <?php if($goods['is_cuxiao'] == 1 ){ ?>checked<?php } ?>>是</label>
                                    <label class="radio-inline i-checks"><input type="radio" name="iscuxiao" value="0" <?php if($goods['is_cuxiao'] == 0 || !isset($goods)){ ?>checked<?php } ?>>否</label>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">促销时间</label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">开&nbsp;始</span>
                                                <input style='width:258px;' readonly class='form-control time_begin' name='time_begin' value="<?php if(strtotime($goods['time_begin'])>0){echo $goods['time_begin'];}; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">结&nbsp;束</span>
                                                <input style='width:258px;' readonly class='form-control time_end' name='time_end' value="<?php if(strtotime($goods['time_end'])>0){echo $goods['time_end'];}; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">展示图片</label>
                                    <div class="col-sm-10">
                                        <?php if ($goodimg) { ?>
                                            <p class="form-control-static lookimg" style="margin:20px">
                                                <?php
                                                foreach ($goodimg as $gimg) {
                                                    echo '<img class="img-thumbnail image" src="' . SITEROOTURL . $gimg . '" style="height:100px;width:100px;"/>';
                                                }
                                                ?>
                                            </p>
                                        <?php } ?>
                                        <div id="uploader" class="wu-example">
                                            <div class="queueList">
                                                <div id="dndArea" class="placeholder">
                                                    <div id="filePicker"></div>
                                                    <p>或将照片拖到这里，单次最多可选300张</p>
                                                </div>
                                            </div>
                                            <div class="statusBar" style="display:none;">
                                                <div class="progress">
                                                    <span class="text">0%</span>
                                                    <span class="percentage"></span>
                                                </div>
                                                <div class="info"></div>
                                                <div class="btns">
                                                    <div id="filePicker2"></div>
                                                    <div class="uploadBtn">开始上传</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-warning">图片大小不超过2MB，图片尺寸最小比例<span
                                                class="imagewh"> 420 x 290</span></div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">产品介绍</label>
                                    <div class="col-sm-10">
                                        <script type="text/plain" id="editor_description" style="height:300px;"><?php echo stripslashes($goods['description']); ?></script>
                                        <input type="hidden" name="description" id="description">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Q&nbsp;&&nbsp;A</label>
                                    <div class="col-sm-10">
                                        <script type="text/plain" id="editor_question" style="height:300px;"><?php echo stripslashes($goods['question']); ?></script>
                                        <input type="hidden" name="question" id="question">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">注意事项</label>
                                    <div class="col-sm-10">
                                        <script type="text/plain" id="editor_attention" style="height:300px;"><?php echo stripslashes($goods['attention']); ?></script>
                                        <input type="hidden" name="attention" id="attention">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">浏览量</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon">虚&nbsp;拟</span>
                                            <input type="number" min="0" name="look_count_basic" id="look_count_basic" class="form-control" value="<?php echo $goods['look_count_basic']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon">真&nbsp;实</span>
                                            <input disabled type="text" name="look_count" id="look_count" class="form-control" value="<?php echo $goods['look_count']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">排&nbsp;序</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="1" name="indexid" id="indexid" placeholder="请输入排序" class="form-control" value="<?php echo $goods['indexid']; ?>">
                                        <div class="alert alert-warning">注：排序根据填写的数字正序展示，数字越小越靠前展示</div>
                                        <span class="help-block m-b-none"></span>
                                    </div>
                                </div>
                                <?php
                                    //if($_GET['cid']){
                                        if($goods['is_new'] == 1 ){ $c="checked";}else{ $c="";}
                                        if($goods['is_new'] == 0 ){ $a="checked";}else{ $a="";}
                                        if($a && $c){ $b="checked";}else{ $b="";}
                                        echo '<div class="form-group">
                                <label class="col-sm-2 control-label">是否新品推荐</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline i-checks"><input type="radio" name="is_new" value="1" '.$c.$b.'>是</label>
                                    <label class="radio-inline i-checks"><input type="radio" name="is_new" value="0" '.$a.$b.'>否</label>
                                </div>
                            </div>';
                                    if($goods['is_item'] == 1 ){ $c="checked";}else{ $c="";}
                                        if($goods['is_item'] == 0 ){ $a="checked";}else{ $a="";}
                                        if($a && $c){ $b="checked";}else{ $b="";}
                                        echo '<div class="form-group">
                                <label class="col-sm-2 control-label">是否特色推荐</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline i-checks"><input type="radio" name="is_item" value="1" '.$c.$b.'>是</label>
                                    <label class="radio-inline i-checks"><input type="radio" name="is_item" value="0" '.$a.$b.'>否</label>
                                </div>
                            </div>';

                                    ///}
                                ?>
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
        <script src="<?php echo SITEROOTURL; ?>admin/js/content.js?v=1.0.0"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/validform/Validform_min.js"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/validform/Validform_Datatype.js"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/webuploader/webuploader.js"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/arttemplate/template.js"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/ueditor1_4_3_2/ueditor.config.js"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/ueditor1_4_3_2/ueditor.all.js"></script>
        <!--时间选择器-->
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
        <!--时间选择器-->
        <!--动态增删输入框-->
        <script src="add.js?v=2.216"></script>
        <!--动态增删输入框-->
        <script>
            //放大镜
            $("body").on("click", ".image", function () {
                var img = new Image(); //图片预加载
                img.src = $(this).attr('src');//获取图片
                var s_w = screen.availWidth;//屏幕宽度
                var s_h = screen.availHeight;//屏幕高度
                var i_w = img.width;//加载的图片宽度
                var i_h = img.height;//加载的图片高度
                var u_w = 0;//实际展示的图片宽度
                var u_h = 0;//实际展示的图片高度
                //图片宽或高超出屏幕宽或高的任意尺寸的0.6(比例随意)
                if (i_w > s_w * 0.6 || i_h > s_h * 0.6) {
                    //图片宽度超出比例大于高度超出比例
                    if (i_w / s_w > i_h / s_h) {
                        u_w = s_w * 0.6;
                        u_h = s_w * 0.6 / i_w * i_h;
                    } else {
                        //图片高度超出比例大于宽度超出比例
                        u_w = s_h * 0.6 / i_h * i_w;
                        u_h = s_h * 0.6;
                    }
                } else {
                    u_w = i_w;
                    u_h = i_h;
                }
                //先关闭所有的页面层
                layer.closeAll('page');
                //在弹出新的页面层
                layer.open({
                    type: 1, //1页面层
                    title: false, //标题
                    closeBtn: 0, //关闭按钮
                    area: [u_w + 'px', u_h + 'px'],
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true, //开启遮罩
                    content: '<img class="img-thumbnail" src="' + img.src + '" width="' + u_w + '" height="' + u_h + '" />'
                });
            });
            $('.time_begin, .time_end').datetimepicker({
                language: 'zh-CN',
                format: 'yyyy-mm-dd hh:ii',
                weekStart: 1,
                startView: 2,
                minView: 0,
                autoclose: true,
                minuteStep: 5
            });
            $(function () {
                var closeLayer = function () {
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.closeLayer(index);
                }
                $('.closelayer').click(function (event) {
                    closeLayer();
                });
                // 动态增加输入框
                return_money_hour(<?php echo $data_info; ?>);
                //富文本编辑
                var ue = UE.getEditor('editor_description', {
                    toolbars: [
                        [
                            'fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'fontborder', '|', 'forecolor', 'insertorderedlist', 'insertunorderedlist', 'cleardoc', 'removeformat', '|',
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
                var ue1 = UE.getEditor('editor_question', {
                    toolbars: [
                        [
                            'fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'fontborder', '|', 'forecolor', 'insertorderedlist', 'insertunorderedlist', 'cleardoc', 'removeformat', '|',
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
                var ue2 = UE.getEditor('editor_attention', {
                    toolbars: [
                        [
                            'fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'fontborder', '|', 'forecolor', 'insertorderedlist', 'insertunorderedlist', 'cleardoc', 'removeformat', '|',
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
                //表单提交
                var form = $('#editForm').Validform({
                    btnSubmit: '#btn-submit',
                    postonce: false,
                    showAllError: true,
                    //msg：提示Banner;
                    //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
                    //cssctl:内置的提示Banner样式控制函数，该函数需传入两个参数：显示提示Banner的对象 和 当前提示的状态（既形参o中的type）;
                    tiptype: function (msg, o) {
                        if (!o.obj.is('form')) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                            var B = $(o.obj.parents('.form-group')[0]);
                            var T = B.find('.help-block');
                            if (o.type == 2) {
                                B.removeClass('has-error').addClass('has-success');
                                T.html('');
                            } else {
                                B.removeClass('has-success').addClass('has-error');
                                T.html("<i class='fa fa-times-circle'></i> " + msg);
                            }
                        }
                    },
                    ajaxPost: true,
                    beforeCheck: function () {
                        $('#description').val(ue.getContent());
                        $('#question').val(ue1.getContent());
                        $('#attention').val(ue2.getContent());
                        // var len = $("input[name='images[]']").length;
                        // for(var i = 0; i < len; i++){
                        //     var str = $("#WU_FILE_"+i).children().eq(2).html();
                        //     var imgReg = /<img.*?(?:>|\/>)/gi;
                        //     //匹配src属性
                        //     var srcReg = /src=[\'\"]?([^\'\"]*)[\'\"]?/i;
                        //     var arr = str.match(imgReg);
                        //    //alert('所有已成功匹配图片的数组：'+arr);
                        //     for (var ii = 0; ii < arr.length; ii++) {
                        //          var src = arr[ii].match(srcReg);
                        //          //获取图片地址
                        //          if(src[1]){
                        //             $("#WU_FILE_"+i).children().eq(1).val(src[1]);
                        //             if(!$("#WU_FILE_"+i).children().eq(1).val()){
                        //                 var img = $('<input type="hidden" name="images[]" value="' + src[1] + '">');
                        //                 $("#WU_FILE_"+i).children().eq(0).next().before(img);
                        //             }
                        //             //console.log(src[1]);
                        //           //alert('已匹配的图片地址'+(i+1)+'：'+src[1]);
                        //          }
                        //      }
                        //      //console.log(str);

                        // }
                        //return false;
                        // $('#pc_content').val(ue1.getContent());
                    },
                    callback: function (data) {
                        if (data.status == 'y') {
                            parent.swal('保存成功！', '', 'success');
                            closeLayer();
                        } else {
                            swal('操作失败', '', 'error');
                        }
                    }
                });
            });
            function return_money_hour(data) {
                if (data == 1234567) {
                    $('.input-group-add').initInputGroup({
                        'widget': '', //输入框组中间的空间类型
                        'data': '',
                    });
                } else {
                    $('.input-group-add').initInputGroup({
                        'widget': '', //输入框组中间的空间类型
                        'data': data,
                    });
                }
            }
        </script>
        <!--图片批量上传-->
        <script type="text/javascript">
            var BASE_URL = '<?php echo SITEROOTURL; ?>admin/js/plugins/webuploader';
            var UPLOAD_URL = '<?php echo SITEROOTURL; ?>admin/module/goods/ajax.php?ac=upload';
            //alert(BASE_URL);
        </script>
        <script src="<?php echo SITEROOTURL; ?>admin/js/demo/webuploader-demo.js?v=0.001"></script>
        <!--图片批量上传-->
    </body>
</html>
