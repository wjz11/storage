<?php

namespace admin\commonProblem;

use inc\BaseAjax;
use inc\Authorize;

class CommonProblem extends BaseAjax
{

    protected function listTable()
    {

        if (checkVar($_GET['search'])) {
            $where['AND']['problem[~]'] = $_GET['search'];
        }
        if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])) {
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        } else {
            $where['ORDER']['indexid'] = 'ASC';
            $where['ORDER']['tbid']    = 'DESC';
        }
        $this->setSortWhere($where);

        $echo['total'] = $this->db->count('tb_common_problem', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_common_problem', ['problem', 'tbid','indexid'], $where);
        foreach ($row as $key => $value) {
            $tmp['indexid'] = $value['indexid'];
            $tmp['problem'] = mb_strlen($value['problem'], 'utf-8') > 50 ? mb_substr($value['problem'], 0, 50, 'utf-8') . '...' : $value['problem'];
            if (Authorize::checkAdminAuthorize('commonProblem.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-danger btn-xs remove" data-id="' . $value['tbid'] . '">删除</a> ';
            }


            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);


    }

    protected function edit()
    {

        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            $this->db->update('tb_common_problem', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_common_problem', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);
    }

    protected function delete()
    {

        $rs = $this->db->delete('tb_common_problem', array('tbid' => $_POST['id']));

        if ($rs > 0) {
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);

    }


}



