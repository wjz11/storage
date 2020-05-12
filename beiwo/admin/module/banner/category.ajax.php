<?php

require '../../../global.php';

switch ($_REQUEST['ac'])
{
    case 'list':
        $where['AND']['isdel[!]'] = 1;
        if ($_GET['search'] != '')
        {
            $where['AND']['name[~]'] = $_GET['search'];
        }
        if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order']))
        {
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        }
        else
        {
            $where['ORDER']['tbid'] = 'ASC';
        }
        if (isset($_GET['pid']) && !empty($_GET['pid']))
        {
            $where['AND']['pid'] = $_GET['pid'];
            $where['AND']['tbid[!]'] =[8,9];//ji
        }
        else
        {
            $where['AND']['pid'] = 0;
        }
        $echo['total'] = $db->count('tb_banner_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $db->select('tb_banner_category', '*', $where);
        foreach ($row as $value)
        {
            $tmp['name'] = "PC官网".$value['name'];
            if (checkAdminAuthorize('banner.category_edit'))
            {
                $text = $value['tbid'] ? '编辑' : '管理';
                if($value['tbid']==6 || $value['tbid']==4){
                     $tmp['do'] = ' <a href="javascript:;" class="btn btn-primary btn-sm edit_c" data-id="' . $value['tbid'] . '">子类管理</a> ';
                 }else{
                     $tmp['do'] = ' <a href="javascript:;" class="btn btn-primary btn-sm edit" data-id="' . $value['tbid'] . '">banner编辑</a> ';
                 }
                if(!isset($_GET['b'])){
                    if($value['tbid']==1){
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-sm item" data-id="' . $value['tbid'] . '">合作伙伴管理</a> ';
                    }else{
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-sm item" data-id="' . $value['tbid'] . '">'.$value['name'].'管理</a> ';
                    }
                }
                
                /**
                $child = $db->count('tb_banner_category', ['pid' => $value['tbid']]);
                if ($value['pid'] == 0 && $child > 0)
                {
                    $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-sm child" data-id="' . $value['tbid'] . '">查看子类</a> ';
                }
                if ($child == 0)
                {
                    $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-sm item" data-id="' . $value['tbid'] . '">'.$value['name'].'管理</a> ';
                }
                **/
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
        break;
    case 'edit':
        $set = [
            'name' => $_POST['title'],
            'width' => $_POST['width'],
            'height' => $_POST['height'],
            'edittime' => date("Y-m-d H:i:s"),
        ];
        if ($_POST['id'] != '')
        {
            $db->update('tb_banner_category', $set, ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        }
        else
        {
            $set['width'] = $_POST['width'];
            $set['height'] = $_POST['height'];
            $set['createtime'] = date("Y-m-d H:i:s");
            $rs = $db->insert('tb_banner_category', $set);
            $cb['status'] = $rs ? 'y' : 'n';
        }
        echo json_encode($cb);
        break;
}
