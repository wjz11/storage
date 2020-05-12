<?php

namespace admin\ConnectUsItem;

use inc\BaseAjax;

class ConnectUsItem extends BaseAjax
{
    /**
     * 列出分类项
     * @Author : bapijun
     */
    protected function listCategory()
    {


        if ($_GET['search'] != '') {
            $where['AND']['name[~]'] = $_GET['search'];
        }


        $this->setSortWhere($where);
        $echo['total'] = $this->db->count('tb_connect_us_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_connect_us_category', ['name', 'tbid'], $where);
        foreach ($row as $value) {

            $tmp['name'] = $value['name'];
            $tmp['num']=$this->db->count('tb_connect_us_item', '*', array("category_id"=>$value['tbid']));
            if (\inc\Authorize::checkAdminAuthorize('connectUs.edit')) {
                // $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs item" data-id="' . $value['tbid'] . '">编辑</a> ';
                //$tmp['do'] = ' <a href="javascript:;" class="btn btn-primary btn-xs remove" data-id="'.$value['tbid'].'">删除</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    /**
     * 列出分项
     * @Author : bapijun
     */
    protected function listItem()
    {


        if ($_GET['search'] != '') {
            $where['AND']['name[~]'] = $_GET['search'];
        }

        $where['AND']['category_id'] = $_GET['cid'];
        $this->setSortWhere($where);
        $echo['total'] = $this->db->count('tb_connect_us_item', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_connect_us_item', ['name', 'tbid'], $where);
        foreach ($row as $value) {

            $tmp['name'] = $value['name'];

            if (\inc\Authorize::checkAdminAuthorize('connectUs.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';

                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs remove" data-id="' . $value['tbid'] . '">删除</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    /**
     * 删除分项
     * @return [type] [description]
     */
    protected function deleteItem()
    {
        $res = $this->db->delete('tb_connect_us_item', ['tbid' => $_POST['id']]);
        if ($res) {
            $cb['status'] = 'y';
            
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }

    /**
     * 编辑联系我们分类
     * @Author : bapijun
     */
    protected function editCategory()
    {

        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            $this->db->update('tb_connect_us_category', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_connect_us_category', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);

    }

    /**
     * 编辑联系我们分项
     * @Author : bapijun
     */
    protected function editItem()
    {
        unset($_POST['ac']);
        if (isset($_POST['is_index']) && !empty($_POST['is_index'])) {
            $_POST['is_index'] = 1;
        }
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            $this->db->update('tb_connect_us_item', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_connect_us_item', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);
    }
}
