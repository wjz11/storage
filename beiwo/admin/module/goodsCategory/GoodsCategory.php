<?php

namespace admin\goodsCategory;

use inc\BaseAjax;
use inc\Authorize;
class GoodsCategory extends BaseAjax {

    protected function listTable()
    {
        if (checkVar($_GET['search']))
        {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        $this->setSortWhere($where);
        $where['category_id'] = $_GET['cid'];
        $echo['total'] = $this->db->count('tb_goods_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $where['ORDER'] = array('indexid' => 'ASC', 'tbid' => 'DESC');
        $echo['rows'] = array();
        $row = $this->db->select('tb_goods_category', '*', $where);
        foreach ($row as $key => $value)
        {
            $tmp=$value;
            if ($value['image_icon_1'] == '')
            {
                $tmp['image_icon_1'] = '<img src="' . SITEROOTURL . 'admin/img/noimg.jpg" height="80" />';
            }
            else
            {
                $tmp['image_icon_1'] = '<img src="' . SITEROOTURL . $value['image_icon_1'] . '" height="80" />';
            }
            $tmp['is_new'] = $value['is_new']==1?"是":"否";
            $tmp['is_item'] = $value['is_item']==1?"是":"否";
            $tmp['indexid'] = $value['indexid'];
            $tmp['name'] = $value['name'];
            if (Authorize::checkAdminAuthorize('goodsCategory.edit'))
            {
                $tmp['do'] = '<a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">分类信息编辑</a> ';
                if ($value['category_id'] == 0)
                {
                    $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs item" data-id="' . $value['tbid'] . '">产品分类管理</a> ';
                }
                else
                {
                    if($_GET['cid']!=3){
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs goods" data-id="' . $value['tbid'] . '">下属商品管理</a> ';
                    }
                    if($_GET['cid']==3){
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs resources_edit" data-id="' . $value['tbid'] . '">下载资料管理</a> ';
                    }
                    $tmp['do'] .= ' <a href="javascript:;" class="btn btn-outline btn-danger btn-xs remove" data-id="' . $value['tbid'] . '">删除</a> ';
                    /**
                    $tmp['do'] .= '
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">更多操作 <span class="caret"></span></button>
                            <ul class="dropdown-menu">';
                    if ($value['category_id'] != 3)
                    {
                        $tmp['do'] .= '<li><a href="javascript:;" class="goods" data-id="' . $value['tbid'] . '">下属商品管理</a></li>';
                    }
                    if ($value['is_new'])
                    {
                        $tmp['do'] .= '<li><a href="javascript:;" class="status" data-id="' . $value['tbid'] . '">取消推荐</a></li>';
                    }
                    else
                    {
                        $tmp['do'] .= '<li><a href="javascript:;" class="status" data-id="' . $value['tbid'] . '">设置推荐</a></li>';
                    }
                    $tmp['do'] .= '<li><a href="javascript:;" class="remove" data-id="' . $value['tbid'] . '">删除</a></li>';
                    $tmp['do'] .= '</ul></div>';
                    **/
                }
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    protected function changeStatus()
    {
        $status = $this->db->get('tb_goods_category', 'is_new', array('tbid' => $_POST['id']));
        if ($status == 1)
        {
            $rs = $this->db->update('tb_goods_category', array('is_new' => 0), array('tbid' => $_POST['id']));
        }
        else
        {
            $rs = $this->db->update('tb_goods_category', array('is_new' => 1), array('tbid' => $_POST['id']));
        }
        $cb['status'] = $rs > 0 ? 'y' : 'n';
        echo json_encode($cb);
    }

    protected function edit()
    {
        if($_POST['images']){
            $_POST['image_icon_1']=$_POST['images'];
        }
        if($_POST['images1']){
            $_POST['image_icon_2']=$_POST['images1'];
        }
        if($_POST['content1']){
            $_POST['intro']=stripslashes($_POST['content1']);
            unset($_POST['content1']);
        }
        if($_POST['intro1']){
            $_POST['intro']=stripslashes($_POST['intro1']);
            unset($_POST['intro1']);
        }
        $_POST['content']=stripslashes($_POST['content']);
        unset($_POST['ac'], $_POST['editorValue']);
        unset($_POST['images'], $_POST['images1']);
        foreach ($_POST as $key => $value)
        {
            $set[$key] = $value; //默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s');
        $set['indexid'] = $set['indexid'] ? $set['indexid'] : 1;
        unset($set['id']);
        if (!empty($_POST['id']))
        {
            $this->db->update('tb_goods_category', $set, ['tbid' => $_POST['id']]);
            logo_record('tb_goods_category',1,'编辑产品分类',$set,1);
            $cb['status'] = 'y';
        }
        else
        {
            $res = $this->db->insert('tb_goods_category', $set);
            logo_record('tb_goods_category',1,'新增产品分类',$set,1);
            $cb['status'] = $res ? 'y' : 'n';
        }
        echo json_encode($cb);
    }

    protected function add_edit()
    {
        if($_POST['intro']){
            $_POST['intro']=stripslashes($_POST['intro']);
        }
        unset($_POST['ac']);
        foreach ($_POST as $key => $value)
        {
            $set[$key] = $value; //默认传入的name等于数据库的column
        }
        unset($set['id']);
        if (!empty($_POST['id']))
        {
            $this->db->update('tb_goods_category_info', $set, ['tbid' => $_POST['id']]);
            logo_record('tb_goods_category',1,'编辑产品信息分类',$set,1);
            $cb['status'] = 'y';
        }
        else
        {
            $res = $this->db->insert('tb_goods_category_info', $set);
            logo_record('tb_goods_category_info',1,'新增产品信息分类',$set,1);
            $cb['status'] = $res ? 'y' : 'n';
        }
        echo json_encode($cb);
    }

    protected function delete()
    {
        $rs = $this->db->delete('tb_goods_category', array('tbid' => $_POST['id']));
        $rs = $this->db->delete('tb_goods', array('goods_category_id' => $_POST['id']));
        logo_record('tb_goods_category',1,'删除产品分类',$_POST['id'],2);
        $cb['status'] = $rs > 0 ? 'y' : 'n';
        echo json_encode($cb);
    }

    protected function uploadFile() {
        include('inc/Uploader.class.php');
        $config       = array(
            'pathFormat' => 'uploads/product/{time}{rand:6}', //保存路径
            'allowFiles' => array('.zip', '.rar', '.png', '.jpg', '.jpeg', '.gif', '.docx', '.doc', '.xls', '.xlsx', '.pdf', '.ppt', '.pptx'), //文件允许格式
            'maxSize'    => 2097152 //文件大小限制，单位B,2M
        );
        $up           = new \Uploader('file', $config);
        $info         = $up->getFileInfo();
        $info['size'] = $this->FileSizeConvert($info['size']);
        $info['type'] = substr($info['type'], 1);
        echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"' . SITEROOTURL . $info['url'] . '","state":"' . $info['state'] . '","size":"' . $info['size'] . '"}';
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

}
