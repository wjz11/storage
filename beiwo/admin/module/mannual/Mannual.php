<?php

namespace admin\mannual;

use inc\BaseAjax;
use inc\Authorize;

class Mannual extends BaseAjax {

    /**
     * 列出分类项
     * @Author : bapijun
     */
    protected function listCategory() {
        if ($_GET['search'] != '') {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        $this->setSortWhere($where);
        $echo['total']  = $this->db->count('tb_mannual_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows']   = array();
        $row            = $this->db->select('tb_mannual_category', ['name', 'tbid'], $where);
        foreach ($row as $value) {
            $tmp['name'] = $value['name'];
            if (\inc\Authorize::checkAdminAuthorize('mannual.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs item" data-id="' . $value['tbid'] . '">查看分项</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    protected function listTable() {
        if (checkVar($_GET['search'])) {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        $this->setSortWhere($where);
        $where['AND']['category_id'] = $_GET['cid'];
        $echo['total']               = $this->db->count('tb_mannual', '*', $where);
        $where['LIMIT']              = array($_GET['offset'], $_GET['limit']);
        $echo['rows']                = array();
        $row                         = $this->db->select('tb_mannual', ['name', 'tbid',], $where);
        foreach ($row as $key => $value) {
            $tmp['name'] = $value['name'];
            if (Authorize::checkAdminAuthorize('mannual.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-danger btn-xs remove" data-id="' . $value['tbid'] . '">删除</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    protected function editCategory() {
        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value; //默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            $this->db->update('tb_mannual_category', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_mannual_category', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
    }

    protected function edit() {
        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value; //默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            $this->db->update('tb_mannual', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {
            $res = $this->db->insert('tb_mannual', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
    }

    protected function delete() {
        $rs = $this->db->delete('tb_mannual', array('tbid' => $_POST['id']));
        if ($rs > 0) {
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }

    protected function uploadFile() {
        include('inc/Uploader.class.php');
        $config       = array(
            'pathFormat' => 'uploads/product/{time}{rand:6}', //保存路径
            'allowFiles' => array('.zip', '.rar', '.png', '.jpg', '.jpeg', '.gif', '.docx', '.doc', '.xlsx', '.pdf'), //文件允许格式
            'maxSize'    => 20480000 //文件大小限制，单位B
        );
        $up           = new \Uploader('file', $config);
        $info         = $up->getFileInfo();
        $info['size'] = $this->FileSizeConvert($info['size']);
        $info['type'] = substr($info['type'], 1);
        echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"' . SITEROOTURL . $info['url'] . '","state":"' . $info['state'] . '","size":"' . $info['size'] . '"}';
    }

    /**
     * 将bytes转化为对应大小
     * @param [type] $bytes [description]
     */
    function FileSizeConvert($bytes) {
        $bytes   = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT"  => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT"  => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT"  => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT"  => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT"  => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = strval(round($result, 0)) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

}
