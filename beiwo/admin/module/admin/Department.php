<?php

namespace admin\admin;

use inc\BaseAjax;
use inc\Authorize;

/**
 * 部门
 * @author yiwang
 *
 */
class Department extends BaseAjax
{

    /**
     * 列出部门
     */
    protected function listDepartment()
    {
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])) {
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        } else {
            $where['ORDER']['tbid'] = 'DESC';
        }

        $where['AND']['level'] = 0;

        $echo['total'] = $this->db->count('tb_admin_department', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_admin_department', '*', $where);
        foreach ($row as $key => $value) {
            $tmp['name'] = $value['name'];
            $tmp['main'] = $value['main'];
            $tmp['mobile'] = $value['mobile'];
            $job = $this->db->select('tb_admin_department', 'tbid', ['parent_lev' => $value['tbid']]);
            if ($job) {
                $tmp['count'] = $this->db->count('tb_admin_member', '*', array('department_id' => $job));
            } else {
                $tmp['count'] = 0;
            }


            if (Authorize::checkAdminAuthorize('admin.edit')) {
                $tmp['do'] = '<a href="javascript:;" class="btn btn-primary btn-sm edit" data-id="' . $value['tbid'] . '"><i class="fa fa-edit"></i> 编辑</a> ';
                $tmp['do'] .= '<a href="javascript:;" class="btn btn-primary btn-sm position" data-id="' . $value['tbid'] . '"><i class="fa fa-edit"></i> 职位管理</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    /**
     * 获取对应权限
     */
    protected function getAuthorizeInfo()
    {
        global $s_admin_authorize;
        $authorizelist = explode(',', $_GET['authorizelist']);
        foreach ($s_admin_authorize as $k => $v) {
            $i = 0;
            foreach ($s_admin_authorize[$k] as $kk => $vv) {
                if ($i != 0) {
                    if (in_array($k . '.' . $kk, $authorizelist)) {
                        $cb[] = $v['_title'] . '：' . $vv;
                    }
                }
                $i++;
            }
        }
        echo json_encode($cb);
    }

    /**
     * 编辑部门
     */
    protected function editDepartment()
    {
        $set = array(
            'name' => $_POST['name'],
            'main' => $_POST['main'],
            'mobile' => $_POST['mobile'],
            'special' => $_POST['special'],
            'intro' => $_POST['intro'],

            'edittime' => date('Y-m-d H:i:s'),
        );
        if ($_POST['id'] != '') {
            $this->db->update('tb_admin_department', $set, array(
                'tbid' => $_POST['id']
            ));
            $cb['status'] = 'y';
        } else {

            $rs = $this->db->insert('tb_admin_department', $set);
            if ($rs) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
    }

    /**
     * 列出职位
     */
    protected function listJob()
    {
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])) {
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        } else {
            $where['ORDER']['tbid'] = 'DESC';
        }

        $where['AND']['level'] = 1;
        $where['AND']['parent_lev'] = $_GET['parent_lev'];
        $echo['total'] = $this->db->count('tb_admin_department', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_admin_department', '*', $where);
        foreach ($row as $key => $value) {
            $tmp['name'] = $value['name'];
            if (Authorize::checkAdminAuthorize('admin.edit')) {
                $tmp['do'] = '<a href="javascript:;" class="btn btn-primary btn-sm edit" data-id="' . $value['tbid'] . '"><i class="fa fa-edit"></i> 编辑</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    /**
     * 编辑职位
     */
    protected function editJob()
    {
        $set = array(
            'name' => $_POST['main'],
            'intro' => $_POST['intro'],
            'edittime' => date('Y-m-d H:i:s'),

        );
        if ($_POST['id'] != '') {
            $this->db->update('tb_admin_department', $set, array(
                'tbid' => $_POST['id']
            ));
            $cb['status'] = 'y';
        } else {
            $set['parent_lev'] = $_POST['parent_lev'];
            $set['level'] = 1;

            $rs = $this->db->insert('tb_admin_department', $set);
            if ($rs) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
    }
}


