<?php
require '../../../global.php';

switch ($_REQUEST['ac']) {
    case 'list':
        $where['AND']['isdel'] = 0;
        //统计数据总量
        $echo['total'] = $db->count('tb_course', '*', $where);
        $where['ORDER']['indexid'] = 'ASC';
        $where['ORDER']['tbid'] = 'DESC';
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $db->select('tb_course', '*', $where);
        foreach ($row as $value) {
            if ($value['image'] == '') {
                $tmp['image'] = '<img class="image" src="' . SITEROOTURL . 'admin/img/noimg.jpg" height="80" />';
            } else {
                $tmp['image'] = '<img class="image" src="' . SITEROOTURL . $value['image'] . '" height="80" />';
            }
            $tmp['intro'] = mb_strlen($value['intro'], 'utf-8') > 20 ? mb_substr($value['intro'], 0, 20, 'utf-8') . '...' : $value['intro'];
            $tmp['show_time'] = date('Y-m', $value['show_time']);
            $tmp['is_index'] = $value['is_index'] ? '展示' : '不展示';
            $tmp['indexid'] = $value['indexid'];
            if (\inc\Authorize::checkAdminAuthorize('aboutUs.edit')) {
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
            'image' => $_POST['image'],
            'intro' => $_POST['intro'],
            'show_time' => strtotime($_POST['show_time']),
            'is_index' => $_POST['is_index'] ? 1 : 0,
            'indexid' => $_POST['indexid'] ? $_POST['indexid'] : 1,
        );
        if ($_POST['id'] != '') {
            $db->update('tb_course', $set, array(
                'tbid' => $_POST['id'],
            ));
            $cb['status'] = 'y';
            logo_record('tb_course',$_POST['id'],'编辑倍沃历程',$set,1);
        } else {
            $rs = $db->insert('tb_course', $set);
            if ($rs) {
                logo_record('tb_course',$_POST['id'],'新增倍沃历程',$set,1);
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
        break;
    case 'remove':
        $rs = $db->update('tb_course', ['isdel' => 1], array('tbid' => $_POST['id']));
        if ($rs > 0) {
            logo_record('tb_course',$_POST['id'],'删除倍沃历程',"",2);
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'upload1':
        include 'inc/Uploader.class.php';
        $config = array(
            'pathFormat' => 'uploads/course/{time}{rand:6}', //保存路径
            'allowFiles' => array('.png', '.jpg', '.jpeg', '.gif'), //文件允许格式
            'maxSize' => 2097152, //文件大小限制，单位B
        );
        $up = new Uploader('file', $config);
        $info = $up->getFileInfo();
        //判断图片上传宽高
        //        $imginfo = getimagesize($info['url']);
        //        if($imginfo[0]!= $_GET['w']){
        ////            if($imginfo[0]!= $_GET['w'] || $imginfo[1]!= $_GET['h']){
        //            echo '{"state":"传输失败,请重新检查图片的宽,高"}';
        //            return false;
        //        }

        if ($info['state'] == 'SUCCESS') {
            $url = explode('/', $info['url']);
            $url[count($url) - 1] = 's_' . $url[count($url) - 1];
            $url = implode('/', $url);
            imgsresize($info['url'], $_GET['w'] = 2048, $_GET['h'] = 1000, $url);
        }
//        oss_upload($info['url']);
        //        oss_upload($url);
        echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"' . SITEROOTURL . $info['url'] . '","state":"' . $info['state'] . '"}';
        break;
}
