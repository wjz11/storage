<?php
require '../../../global.php';

switch ($_REQUEST['ac']) {
    case 'list':
        $where['AND']['type'] = 2;
        $where['AND']['isdel'] = 0;
        //搜索
        if ($_GET['search'] != '') {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        //统计数据总量
        $echo['total'] = $db->count('tb_server_text', '*', $where);
        //表头排序
        if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])) {
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        } else {
            $where['ORDER']['indexid'] = 'ASC';
            $where['ORDER']['tbid'] = 'DESC';
        }
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $db->select('tb_server_text', '*', $where);
        foreach ($row as $value) {
            $tmp['title'] = $value['title'];
            $tmp['createtime'] = $value['createtime'];
            $tmp['edittime'] = $value['edittime'];
            $tmp['indexid'] = $value['indexid'];
            $tmp['is_use'] = $value['is_use']?'展示':'隐藏';
            if (checkAdminAuthorize('services_support.support_edit')) {
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
            'title' => $_POST['title'],
            'indexid' => $_POST['indexid'] ? $_POST['indexid'] : 1,
            'file' => $_POST['file_name'],
            'url' => stripslashes($_POST['url']),
            //'is_use' => $_POST['is_use'] ? 1 : 0,
            'edittime' => date("Y-m-d H:i:s"),
            'file_type' => $_POST['file_type'],
            'size' => $_POST['size'],
            'url_type' => $_POST['type'],
            //'content' => $_POST['content'],
        );
        if ($_POST['id'] != '') {
            $db->update('tb_server_text', $set, array(
                'tbid' => $_POST['id'],
            ));
            $cb['status'] = 'y';
        } else {
            $set['type'] = 2;
            $set['createtime'] = date("Y-m-d H:i:s");
            $rs = $db->insert('tb_server_text', $set);
            if ($rs) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
        break;
    case 'remove':
        $rs = $db->update('tb_server_text', ['isdel' => 1], array('tbid' => $_POST['id']));
        if ($rs > 0) {
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'uploadFile':
        include('inc/Uploader.class.php');
        $config       = array(
            'pathFormat' => 'uploads/services_support/{time}{rand:6}', //保存路径
            'allowFiles' => array('.zip', '.rar', '.png', '.jpg', '.jpeg', '.gif', '.docx', '.doc', '.xls', '.xlsx', '.pdf', '.ppt', '.pptx'), //文件允许格式
            'maxSize'    => 2097152 //文件大小限制，单位B,2M
        );
        $up           = new \Uploader('file', $config);
        $info         = $up->getFileInfo();
        $info['size'] = FileSizeConvert($info['size']);
        $info['type'] = substr($info['type'], 1);
        echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"' . SITEROOTURL . $info['url'] . '","state":"' . $info['state'] . '","size":"' . $info['size'] . '"}';
    break;
}

    function FileSizeConvert($bytes) {
        $bytes   = floatval($bytes);
        $arBytes = array(
            0 => array(
                "unit"  => "TB",
                "value" => pow(1024, 4)
            ),
            1 => array(
                "unit"  => "GB",
                "value" => pow(1024, 3)
            ),
            2 => array(
                "unit"  => "MB",
                "value" => pow(1024, 2)
            ),
            3 => array(
                "unit"  => "KB",
                "value" => 1024
            ),
            4 => array(
                "unit"  => "B",
                "value" => 1
            ),
        );
        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["value"]) {
                $result = $bytes / $arItem["value"];
                $result = strval(round($result, 0)) . " " . $arItem["unit"];
                break;
            }
        }
        return $result;
    }
