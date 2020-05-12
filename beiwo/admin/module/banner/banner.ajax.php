<?php

require '../../../global.php';

switch ($_REQUEST['ac']) {
    case 'list':
        //高级过滤
        if ($_GET['banner_category_id'] != '') {
            if ($_GET['banner_category_id'] != 'all') {
                $where['AND']['banner_category_id'] = $_GET['banner_category_id'];
            }
        }
        //搜索
        if ($_GET['search'] != '') {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        //统计数据总量
        $echo['total'] = $db->count('tb_banner', '*', $where);
        //表头排序
        if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])) {
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        } else {
            $where['ORDER']['indexid'] = 'ASC';
            $where['ORDER']['tbid']    = 'DESC';
        }
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows']   = array();
        $row            = $db->select('tb_banner', '*', $where);
        foreach ($row as $value) {
            if ($value['image'] == '') {
                $tmp['image'] = '<img class="image" src="' . SITEROOTURL . 'admin/img/noimg.jpg" height="80" />';
            } else {
                $tmp['image'] = '<img class="image" src="' . SITEROOTURL . $value['image'] . '" width="180" height="100" />';
            }
            $tmp['banner_category_id'] = $db->get('tb_banner_category', 'name', array('tbid' => $value['banner_category_id']));
            $tmp['name']               = $value['name'];
            $admin_member_id           = $db->get('tb_admin_member', 'username', array('tbid' => $value['admin_member_id']));
            $tmp['admin_member_id']    = !empty($admin_member_id) ? $admin_member_id : '';
            $tmp['createtime']         = $value['createtime'];
            $tmp['edittime']           = $value['edittime'];
            $tmp['indexid']            = $value['indexid'];
            if (checkAdminAuthorize('banner.image_edit')) {
                $tmp['do'] = ' <a href="javascript:;" class="btn btn-primary btn-sm edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <button type="button" class="btn btn-danger btn-sm remove" data-id="' . $value['tbid'] . '">删除</button> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
        break;
    case 'edit':
        $set = array(
            'image'    => $_POST['image'],
            'name'     => $_POST['name'],
            'url'      => $_POST['url'],
            'indexid'  => $_POST['indexid'] ? $_POST['indexid'] : 1,
            'is_use'   => $_POST['is_use'] ? 1 : 0,
            'edittime' => date("Y-m-d H:i:s"),
            'admin_member_id' => session("admin_id"),
            'resource_id' => $_POST['resource_id'],
            'type' => $_POST['type'],
        );
        if ($_POST['id'] != '') {
            $db->update('tb_banner', $set, array(
                'tbid' => $_POST['id'],
            ));
            logo_record('tb_banner',$_POST['id'],'修改banner',$set,1);
            $cb['status'] = 'y';
        } else {
            $set['banner_category_id'] = $_POST['banner_category_id'];
            $set['createtime']         = date("Y-m-d H:i:s");
            $set['admin_member_id']    = session('admin_id');
            $rs                        = $db->insert('tb_banner', $set);
            if ($rs) {
                logo_record('tb_banner',$_POST['id'],'新增banner',$set,1);
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
        break;
    case 'delete':
        $rs = $db->delete('tb_banner', array('tbid' => $_POST['id']));
        if ($rs > 0) {
            logo_record('tb_banner',$_POST['id'],'删除banner',"",1);
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'upload1':
        include 'inc/Uploader.class.php';
        $config = array(
            'pathFormat' => 'uploads/banner/{time}{rand:6}', //保存路径
            'allowFiles' => array('.png', '.jpg', '.jpeg', '.gif'), //文件允许格式
            'maxSize'    => 2097152, //文件大小限制，单位B
        );
        $up     = new Uploader('file', $config);
        $info   = $up->getFileInfo();
        //print_r($info);die;
        //判断图片上传宽高
        //        $imginfo = getimagesize($info['url']);
        //        if($imginfo[0]!= $_GET['w']){
        ////            if($imginfo[0]!= $_GET['w'] || $imginfo[1]!= $_GET['h']){
        //            echo '{"state":"传输失败,请重新检查图片的宽,高"}';
        //            return false;
        //        }

        if ($info['state'] == 'SUCCESS') {
            $url                  = explode('/', $info['url']);
            $url[count($url) - 1] = 's_' . $url[count($url) - 1];
            $url                  = implode('/', $url);
            imgsresize($info['url'], $_GET['w'], $_GET['h'], $url);
            //$info['url']= $url;
        }
//        oss_upload($info['url']);
        //        oss_upload($url);
        echo '{"url":"' .$info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"' . SITEROOTURL . $info['url'] . '","state":"' . $info['state'] . '"}';
        break;
}
