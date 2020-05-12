<?php
use \inc\Authorize;
require('../../../global.php');
Authorize::checkAdminAuthorizeAndJump('connectUs.edit');
if (!empty($_GET['id'])) {
    $row = $db->get('tb_connect_us_item', '*', ['tbid' => $_GET['id']]);
    $url='http://restapi.amap.com/v3/geocode/geo?address='.$row['address'].'&output=JSON&key=5e00ee8c5198384f17b6122c05431c61';
    //var_dump($url);
    $data = curl_get_https($url);
    //print_r(json_decode($data,true));
    $data = json_decode($data,true);
    if (!empty($data)) {
        //print_r($data['status']);
        if($data['status']==1){
            $jwd = $data['geocodes'][0]['location'];
            //print_r($jwd);
            $arr = explode(",",$jwd);
            $row['lat'] =$arr[1];
            $row['lng'] = $arr[0];
        }
        
        //返回经纬度结果
    }
    //var_dump($row);
    $_GET['cid'] = $row['category_id'];
}

$categoryName = $db->get('tb_connect_us_category', 'name', ['tbid' => $_GET['cid']]);
function curl_get_https($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);
    return $tmpInfo;    //返回json对象
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
                <h3>
                    <button type="button" class="btn btn-white btn-sm closelayer pull-right">返回</button>
                   编辑分项
                </h3>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <form class="form-horizontal" id="editForm" action="ajax.php">
                            <input type="hidden" name="ac" value="editItem">
                            <input type="hidden" name="id" value="<?php echo $row['tbid']; ?>">
                            <input type="hidden" name="category_id" value="<?php echo $_GET['cid']; ?>">
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" placeholder="请输入名称" class="form-control" value="<?php echo $row['name']; ?>" dataType="*" nullmsg="请输入名称" errormsg="请输入名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">区域</label>
                                <div class="col-sm-10">
                                    <input type="text" name="province" id="province" placeholder="请输入区域" class="form-control" value="<?php echo $row['province']; ?>" dataType="*" nullmsg="请输入区域" errormsg="请输入区域">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-sm-2 control-label">地址</label>
                                <div class="col-sm-10">
                                    <input type="text" name="address" id="address" placeholder="请输入地址" class="form-control" value="<?php echo $row['address']; ?>" dataType="*" nullmsg="请输入地址" errormsg="请输入地址">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">电话</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" id="phone" placeholder="请输入总机" class="form-control" value="<?php echo $row['phone']; ?>"  nullmsg="请输入电话" errormsg="请输入电话">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">总机</label>
                                <div class="col-sm-10">
                                    <input type="text" name="switchboard" id="switchboard" placeholder="请输入总机" class="form-control" value="<?php echo $row['switchboard']; ?>"  nullmsg="请输入总机" errormsg="请输入总机">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">email</label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" id="email" placeholder="请输入email" class="form-control" value="<?php echo $row['email']; ?>" ignore="ignore"  dataType="e" nullmsg="请输入email" errormsg="请输入email">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">排序</label>
                                <div class="col-sm-10">
                                    <input type="text" name="indexid" id="indexid" placeholder="请输入排序" class="form-control" value="<?php echo $row['indexid']; ?>" ignore="ignore" dataType="n" nullmsg="请输入排序" errormsg="请输入排序">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">首页底部展示</label>
                                <div class="col-sm-10" style="padding-top:7px;">
                                    <div class="onoffswitch">
                                        <input id="is_index" name="is_index" class="onoffswitch-checkbox"
                                               type="checkbox" <?php if ($row['is_index']) { ?> checked="" <?php } ?>>
                                        <label class="onoffswitch-label" for="is_index">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">详细地址</label>
                                <div class="col-sm-8">
                                    <input type="text" name="address" id="address" placeholder="请输入详细地址" class="form-control" value="<?php echo $row['address']; ?>" dataType="*" nullmsg="请输入详细地址" >
                                    <input type="hidden" name="lng" id="lng" dataType="*" nullmsg="请输入正确的地址" value="<?php echo $row['lng']?$row['lng']:""; ?>">
                                    <input type="hidden" name="lat" id="lat" dataType="*" nullmsg="请输入正确的地址" value="<?php echo $row['lat']?$row['lat']:""; ?>">
                                    <span class="help-block m-b-none" id="message"></span>
                                </div>
                                <div id="message"></div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div id="container" style="height:400px;margin-left: 15px;" class="col-sm-9"></div>
                                <span class="help-block m-b-none"></span>
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
    <script src="<?php echo SITEROOTURL; ?>/admin/js/plugins/arttemplate/template.js"></script>
    <script>
    $(function(){
        var closeLayer = function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.closeLayer(index);
        }
        $('.closelayer').click(function(event){
            closeLayer();
        });
         $('#address').click(function(event){
            var lng = $('input[name=lng]').val();
            var lat = $('input[name=lat]').val();
            if( !lng || !lat ){
                var lng = 120.146511;
                var lat = 30.244248;
            }

            var map = new AMap.Map('container', {
                zoom: 12,                         //地图显示区域大小
                center: [lng, lat],             //初始定位坐标
            });

            AMap.plugin(['AMap.ToolBar', 'AMap.Scale'], function() {
                var toolBar = new AMap.ToolBar();
                var scale = new AMap.Scale();
                map.addControl(toolBar);
                map.addControl(scale);
            });

            var marker = new AMap.Marker({       //点设置
                position: map.getCenter(),
                draggable: true,
                cursor: 'move',
                raiseOnDrag: false,
                zIndex: 101,
                raiseOnDrag: true
            });
            marker.setMap(map);                  //将点绘制到地图上

            $('input[name=lng]').val(marker.getPosition().lng);
            $('input[name=lat]').val(marker.getPosition().lat);

            AMap.event.addListener(marker, "dragstart", function(e) {     //创建点拖动监听--开始
                $('input[name=lng]').val(marker.getPosition().lng);
                $('input[name=lat]').val(marker.getPosition().lat);
            });

            AMap.event.addListener(marker, "dragging", function(e) {      //创建点拖动监听--过程
                $('input[name=lng]').val(marker.getPosition().lng);
                $('input[name=lat]').val(marker.getPosition().lat);
            });

            AMap.event.addListener(marker, "dragend", function(e) {       //创建点拖动后松开监听
                $('input[name=lng]').val(marker.getPosition().lng);
                $('input[name=lat]').val(marker.getPosition().lat);
            });
            var auto = new AMap.Autocomplete({             //获取地址
                input: "address"
            });
            AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
            function select(e) {                           //关键字搜索 将地址转换成经纬度
                if (e.poi && e.poi.location) {
                    map.setZoom(20);
                    marker.setPosition(e.poi.location);
                    $('input[name=lng]').val(marker.getPosition().lng);
                    $('input[name=lat]').val(marker.getPosition().lat);
                    map.setCenter(e.poi.location);
                }
            }
            AMap.plugin('AMap.Geocoder',function(){
                var geocoder = new AMap.Geocoder({
                    city: ""//城市，默认：“全国”
                });
                map.on('click',function(e){                //在地图点击时
                    marker.setPosition(e.lnglat);          //创建点
                    geocoder.getAddress(e.lnglat,function(status,result){     //将当前点击的地址转成经纬度  e.lnglat : x,y  经纬度
                      if(status=='complete'){
                            $('input[name=lng]').val(marker.getPosition().lng);
                            $('input[name=lat]').val(marker.getPosition().lat);
                            $('input[name=address]').val(result.regeocode.formattedAddress)
                      }
                    })
                })

            });
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
    });
    </script>
    <!--  地图  -->
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.4.3&key=bc07c58be1aeef3fb08b7f0e161ce98f&plugin=AMap.Autocomplete,AMap.PlaceSearch,AMap.Geocoder"></script>
    <!-- UI组件库 1.0 -->
    <script src="//webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>

    <!-- 地图  -->
    <script type="text/javascript">

    var lng = $('input[name=lng]').val();
    var lat = $('input[name=lat]').val();
    if( !lng || !lat ){
        var lng = 120.146511;
        var lat = 30.244248;
    }

    var map = new AMap.Map('container', {
        zoom: 12,                         //地图显示区域大小
        center: [lng, lat],             //初始定位坐标
    });

    AMap.plugin(['AMap.ToolBar', 'AMap.Scale'], function() {
        var toolBar = new AMap.ToolBar();
        var scale = new AMap.Scale();
        map.addControl(toolBar);
        map.addControl(scale);
    });

    var marker = new AMap.Marker({       //点设置
        position: map.getCenter(),
        draggable: true,
        cursor: 'move',
        raiseOnDrag: false,
        zIndex: 101,
        raiseOnDrag: true
    });
    marker.setMap(map);                  //将点绘制到地图上

    $('input[name=lng]').val(marker.getPosition().lng);
    $('input[name=lat]').val(marker.getPosition().lat);

    AMap.event.addListener(marker, "dragstart", function(e) {     //创建点拖动监听--开始
        $('input[name=lng]').val(marker.getPosition().lng);
        $('input[name=lat]').val(marker.getPosition().lat);
    });

    AMap.event.addListener(marker, "dragging", function(e) {      //创建点拖动监听--过程
        $('input[name=lng]').val(marker.getPosition().lng);
        $('input[name=lat]').val(marker.getPosition().lat);
    });

    AMap.event.addListener(marker, "dragend", function(e) {       //创建点拖动后松开监听
        $('input[name=lng]').val(marker.getPosition().lng);
        $('input[name=lat]').val(marker.getPosition().lat);
    });
    var auto = new AMap.Autocomplete({             //获取地址
        input: "address"
    });
    AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
    function select(e) {                           //关键字搜索 将地址转换成经纬度
        if (e.poi && e.poi.location) {
            map.setZoom(20);
            marker.setPosition(e.poi.location);
            $('input[name=lng]').val(marker.getPosition().lng);
            $('input[name=lat]').val(marker.getPosition().lat);
            map.setCenter(e.poi.location);
        }
    }
    AMap.plugin('AMap.Geocoder',function(){
        var geocoder = new AMap.Geocoder({
            city: ""//城市，默认：“全国”
        });
        map.on('click',function(e){                //在地图点击时
            marker.setPosition(e.lnglat);          //创建点
            geocoder.getAddress(e.lnglat,function(status,result){     //将当前点击的地址转成经纬度  e.lnglat : x,y  经纬度
              if(status=='complete'){
                    $('input[name=lng]').val(marker.getPosition().lng);
                    $('input[name=lat]').val(marker.getPosition().lat);
                    $('input[name=address]').val(result.regeocode.formattedAddress)
              }
            })
        })

    });
    </script>
</body>
</html>
