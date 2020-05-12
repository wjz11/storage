<?php

require('../../../global.php');

switch ($_REQUEST['ac'])
{
    case 'list':
        if (!checkAdminAuthorize('news.browse'))
        {

            $echo['status'] = '权限错误';
            echo json_encode($echo);
            exit();
        }
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
            $where['ORDER']['indexid'] = 'DESC';
        }
        $where['AND']['news_category_id'] = $_GET['cid'];
        $echo['total'] = $db->count('tb_news', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $db->select('tb_news', '*', $where);
        foreach ($row as $value)
        {
            $row=$value;
            if ($value['image'] == '')
            {
                $tmp['image'] = '<img src="' . SITEROOTURL . 'admin/img/noimg.jpg" height="80" />';
            }
            else
            {
                $tmp['image'] = '<img src="' . YSITEROOTURL . $value['image'] . '" height="80" />';
            }
            $tmp['name'] = $value['name'];

            $tmp['createtime'] = $value['createtime'];

            if (checkAdminAuthorize('news.edit'))
            {
                $value['is_top']=$value['is_top']==1?"是":"否";
                $value['is_index']=$value['is_index']==1?"是":"否";
                $createtime="发布时间：".$value['createtime']."</br>";
                $edittime="最后编辑时间：".$value['edittime']."</br>";
                $admin_id=$db->get("tb_admin_member","realname",array("tbid"=>$value['admin_id']));
                $admin_id=$admin_id?$admin_id:"";
                $admin_id="最后编辑人：".$admin."</br>";
                $value['info']=$createtime.$edittime.$admin_id;
                $tmp=$value;
                if ($value['image'] == '') {
                    $tmp['image'] = '<img class="image" src="' . SITEROOTURL . 'admin/img/noimg.jpg" height="80" />';
                } else {
                    $tmp['image'] = '<img class="image" src="' . SITEROOTURL . $value['image'] . '" height="80" />';
                }
                $tmp['do'] = ' <a href="javascript:;" class="btn btn-primary  btn-xs  edit" data-id="' . $value['tbid'] . '"> 编辑</a> ';
                $tmp['do'] .= ' <button type="button" class="btn btn-danger btn-outline  btn-xs remove" data-id="' . $value['tbid'] . '">删除</button> ';

                /**
                $type = $db->get('tb_news_category', 'type', ['tbid' => $value['news_category_id']]);
                if (1 == $type)
                {//第一类新闻才有这一类标签
                    if ($value['is_top'] == 1)
                    {
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs status" data-id="' . $value['tbid'] . '" data-type="top">取消新闻置顶</a> ';
                    }
                    else
                    {
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs status" data-id="' . $value['tbid'] . '" data-type="top">新闻置顶</a> ';
                    }
                    if ($value['is_hot'] == 1)
                    {
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs status" data-id="' . $value['tbid'] . '" data-type="hot">取消热门</a> ';
                    }
                    else
                    {
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs status" data-id="' . $value['tbid'] . '" data-type="hot">热门</a> ';
                    }
                    if ($value['is_banner'] == 1)
                    {
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs status" data-id="' . $value['tbid'] . '" data-type="banner">取消图片置顶</a> ';
                    }
                    else
                    {
                        $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs status" data-id="' . $value['tbid'] . '" data-type="banner">图片置顶</a> ';
                    }
                }
                **/
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
        break;
    case 'changeStatus':
        $type = 'is_' . $_POST['type'];
        $status = $db->get('tb_news', $type, array('tbid' => $_POST['id']));
        if ($status == 1)
        {
            $rs = $db->update('tb_news', array($type => 0), array('tbid' => $_POST['id']));
        }
        else
        {
            $rs = $db->update('tb_news', array($type => 1), array('tbid' => $_POST['id']));
        }
        if ($rs > 0)
        {
            $cb['status'] = 'y';
        }
        else
        {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'edit':
        if (!checkAdminAuthorize('news.edit'))
        {

            $echo['status'] = '权限错误';
            echo json_encode($echo);
            exit();
        }
        $set = array(
            'image' => $_POST['image'],
            'name' => $_POST['name'],
            'author' => $_POST['author'],
            'intro' => $_POST['intro'],
            'is_top' => $_POST['is_top'],
            'is_index' => $_POST['is_index'],
            'content' => stripslashes($_POST['content']),
            'news_category_id' => $_POST['news_category_id'],
            'edittime' => date("Y-m-d H:i:s"),
            'indexid' => $_POST['indexid'],
        );
        // if (2 == $_POST['news_category_id'])
        // {
        //     $set['begintime'] = $_POST['begintime'];
        //     $set['endtime'] = $_POST['endtime'];
        // }
        if ($_POST['id'] != '')
        {
            //  logo_record('tb_news', $_POST['id'],'更新公告', $set, 1);
            $db->update('tb_news', $set, array(
                'tbid' => $_POST['id']
            ));
            logo_record('tb_news',1,'编辑新闻',$set,1);
            $cb['status'] = 'y';
        }
        else
        {
            $set['createtime'] = date("Y-m-d H:i:s");
            // logo_record('tb_news', '','更新公告', $set, 1);
            $rs = $db->insert('tb_news', $set);
            if ($rs)
            {
                logo_record('tb_news',1,'新增新闻',$set,1);
                $cb['status'] = 'y';
            }
            else
            {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
        break;
    case 'delete':
        if (!checkAdminAuthorize('news.edit'))
        {

            $echo['status'] = '权限错误';
            echo json_encode($echo);
            exit();
        }
        //logo_record('tb_news', $_POST['id'],'删除公告', $set, 2);
        $rs = $db->delete('tb_news', array('tbid' => $_POST['id']));
        if ($rs > 0)
        {
            logo_record('tb_news',1,'删除新闻信息',$set,2);
            $cb['status'] = 'y';
        }
        else
        {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'upload':
        if (!checkAdminAuthorize('news.edit'))
        {

            $echo['status'] = '权限错误';
            echo json_encode($echo);
            exit();
        }
        include('inc/Uploader.class.php');
        $config = array(
            'pathFormat' => 'uploads/news/{time}{rand:6}', //保存路径
            'allowFiles' => array('.png', '.jpg', '.jpeg', '.gif'), //文件允许格式
            'maxSize' => 2097152 //文件大小限制，单位B
        );
        $up = new Uploader('file', $config);
        $info = $up->getFileInfo();
        if ($info['state'] == 'SUCCESS')
        {
            $url = explode('/', $info['url']);
            $url[count($url) - 1] = 's_' . $url[count($url) - 1];
            $url = implode('/', $url);
            imgsresize($info['url'], 400, 400, $url);
        }

        echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"' . SITEROOTURL . $info['url'] . '","state":"' . $info['state'] . '"}';
        break;
}
?>
