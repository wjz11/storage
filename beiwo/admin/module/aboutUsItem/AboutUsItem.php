<?php

namespace admin\AboutUsItem;

use inc\BaseAjax;

class AboutUsItem extends BaseAjax
{
    /**
     * 列出分类项
     * @Author : bapijun
     */
    protected function listCategory()
    {


        $where['AND']['isdel[!]'] = 1;
        if ($_GET['search'] != '') {
            $where['AND']['name[~]'] = $_GET['search'];
        }


        $this->setSortWhere($where);
        $echo['total'] = $this->db->count('tb_about_us_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_about_us_category', ['name', 'tbid'], $where);
        foreach ($row as $value) {

            $tmp['name'] = $value['name'];

            if (\inc\Authorize::checkAdminAuthorize('aboutUs.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-danger btn-xs remove" data-id="' . $value['tbid'] . '">删除</a> ';
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
            $where['AND']['content[~]'] = $_GET['search'];
        }

        $where['AND']['category_id'] = $_GET['cid'];
        $this->setSortWhere($where);
        $echo['total'] = $this->db->count('tb_about_us_item', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_about_us_item', ['content', 'tbid', 'createtime'], $where);
        foreach ($row as $value) {
            $tmp['createtime'] = $value['createtime'];
            if (strlen($value['content']) > 50) {
                $tmp['content'] = substr($value['content'], 50) . '...';
            } else {
                $tmp['content'] = $value['content'];
            }


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
        $res = $this->db->update('tb_about_us_item', ['isdel' => 1], ['tbid' => $_POST['id']]);
        if ($res) {
            $cb['status'] = 'y';
            logo_record('tb_about_us_item',$_POST['id'],'删除关于倍沃',"",1);
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }

    /**
     * 删除分项
     * @return [type] [description]
     */
    protected function deleteCategory()
    {
        $res = $this->db->update('tb_about_us_category', ['isdel' => 1], ['tbid' => $_POST['id']]);
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
        unset($_POST['editorValue']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            $this->db->update('tb_about_us_category', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_about_us_category', $set);
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
        unset($_POST['editorValue']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {

            $this->db->update('tb_about_us_item', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_about_us_item', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);
    }
    /**
     * 编辑倍沃历程信息
     * @Author : bapijun
     */
    protected function edit_info()
    {

        $set['course']=$_POST['content'];
        $set['edittime'] = date('Y-m-d H:i:s');
        unset($set['id']);
        if (!empty($_POST['id'])) {
            logo_record('tb_about_us',1,'修改倍沃历程信息',$set,1);
            $this->db->update('tb_about_us', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        } else {

            $res = $this->db->insert('tb_about_us', $set);
            if ($res) {
                $cb['status'] = 'y';
            } else {
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);
    }
}
