<?php

namespace admin\goods;

use inc\BaseAjax;

class Goods extends BaseAjax {

    protected function listTable() {
        if ($_GET['search'] != '') {
            $where['OR']['name[~]']        = $_GET['search'];
            $where['OR']['item_number[~]'] = $_GET['search'];
            $where['OR']['intro[~]']       = $_GET['search'];
            $where['OR']['format[~]']      = $_GET['search'];
        }
        $where['AND']['goods_category_id'] = $_GET['cid'];
        $this->setSortWhere($where);
        $echo['total']                     = $this->db->count('tb_goods', '*', $where);
        $where['LIMIT']                    = array($_GET['offset'], $_GET['limit']);
        $where['ORDER']                    = array('indexid' => 'ASC', 'tbid' => 'DESC');
        $echo['rows']                      = array();
        $row                               = $this->db->select('tb_goods', '*', $where);
        foreach ($row as $value) {
            $value['is_new']=$value['is_new']==1?"是":"否";
            $value['is_item']=$value['is_item']==1?"是":"否";
            $createtime="发布时间：".$value['createtime']."</br>";
            $edittime="最后编辑时间：".$value['edittime']."</br>";
            $admin_id=$this->db->get("tb_admin_member","realname",array("tbid"=>$value['admin_id']));
            $admin_id=$admin_id?$admin_id:"";
            $admin_id="最后编辑人：".$admin."</br>";
            $value['info']=$createtime.$edittime.$admin_id;
            $tmp=$value;
            $tmp['indexid']     = $value['indexid'];
            $tmp['name']        = $value['name'];
            $tmp['english_name'] = $value['english_name'];
            $article_number=$this->db->get("tb_goods_sku","article_number",array("goods_id"=>$value['tbid']));
            $tmp['item_number'] = $value['item_number']?$value['item_number']:$article_number;
            $tmp['intro']       = $value['intro'];
            $tmp['format']      = $value['format'];
            $tmp['createtime']  = $value['createtime'];
            $tmp['edittime']    = $value['edittime'];
            if (\inc\Authorize::checkAdminAuthorize('goods.edit')) {
                $tmp['do'] = ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="' . $value['tbid'] . '">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-info btn-xs resources_edit" data-id="' . $value['tbid'] . '">下载资料管理</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-danger btn-xs remove" data-id="' . $value['tbid'] . '">删除</a> ';
                /**
                $tmp['do'] .= '
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">更多操作 <span class="caret"></span></button>
                        <ul class="dropdown-menu">';
                $tmp['do'] .= '<li><a href="javascript:;" class="resources_edit" data-id="' . $value['tbid'] . '">资料编辑</a></li>';
                if ($value['is_new']) {
                    $tmp['do'] .= '<li><a href="javascript:;" class="status" data-id="' . $value['tbid'] . '">取消推荐</a></li>';
                } else {
                    $tmp['do'] .= '<li><a href="javascript:;" class="status" data-id="' . $value['tbid'] . '">设置推荐</a></li>';
                }
                $tmp['do'] .= '<li><a href="javascript:;" class="remove" data-id="' . $value['tbid'] . '">删除</a></li>';
                $tmp['do'] .= '</ul></div>';
                **/
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }

    protected function edit() {
        $set['name']         = stripslashes($_POST['name']);
        $set['english_name'] = stripslashes($_POST['english_name']);
        $set['intro']        = stripslashes($_POST['intro']);
        if (isset($_POST['data_array']) && !empty($_POST['data_array']) && is_array($_POST['data_array'])) {
            $sku_set = [];
            if(!empty($_POST['data_array'][0]['field1'])){
                
                foreach ($_POST['data_array'] as $sku) {
                    $tmp                    = [];
                    $tmp['tbid']            = stripslashes($sku['field0']);
                    $tmp['article_number']  = stripslashes($sku['field1']);
                    $tmp['specifications']  = stripslashes($sku['field2']);
                    $tmp['list_price']      = (int) $sku['field3'] * 100;
                    $tmp['promotion_price'] = (int) $sku['field4'] * 100;
                    $sku_set[]              = $tmp;
                }
                $substr = substr($_POST['data_array'][0]['field1'],0,strripos($_POST['data_array'][0]['field1'],'-'));
                $set['item_number'] = stripslashes($substr);
            }
        }
        $set['time_begin'] = stripslashes($_POST['time_begin']);
        $set['time_end']   = stripslashes($_POST['time_end']);
        if (isset($_POST['images']) && !empty($_POST['images']) && is_array($_POST['images'])) {
            $set['image'] = implode('<{|}>', $_POST['images']);
        }
        $set['description']      = stripslashes($_POST['description']);
        $set['question']         = stripslashes($_POST['question']);
        $set['attention']        = stripslashes($_POST['attention']);
        $set['look_count_basic'] = stripslashes($_POST['look_count_basic']);
        $set['indexid']          = stripslashes($_POST['indexid']);
        $set['is_cuxiao'] = stripslashes($_POST['iscuxiao']);
        $set['is_new'] = stripslashes($_POST['is_new']);
        $set['is_item'] = stripslashes($_POST['is_item']);
        $set['edittime']         = date('Y-m-d H:i:s');
        $set['goods_category_id']  = $_POST['goods_category_id'];
        if (!empty($_POST['id'])) {
            try {
                $rs = $this->db->update('tb_goods', $set, ['tbid' => $_POST['id']]);
                $rs = $this->db->update('tb_goods_sku', ['isdel' => 1], ['goods_id' => $_POST['id']]);
                if (isset($_POST['data_array']) && !empty($_POST['data_array']) && is_array($_POST['data_array'])) {
                    foreach ($sku_set as $k => $s) {
                        $data_id       = $s['tbid'];
                        unset($s['tbid']);
                        $s['goods_id'] = $_POST['id'];
                        $s['isdel']    = 0;
                        if ($data_id) {
                            $this->db->update('tb_goods_sku', $s, ['tbid' => $data_id]);
                        } else {
                            $this->db->insert('tb_goods_sku', $s);
                        }
                    }
                }
                $cb['status'] = 'y';
                logo_record('tb_goods',1,'编辑商品',$set,1);
            } catch (Exception $exc) {
                $cb['status'] = 'n';
            }
        } else {
            try {
                $rs = $this->db->insert('tb_goods', $set);
                if (isset($_POST['data_array']) && !empty($_POST['data_array']) && is_array($_POST['data_array'])) {
                    if($sku_set){
                        foreach ($sku_set as $k => $s) {
                            unset($s['tbid']);
                            $s['goods_id'] = $_POST['id'];
                            $this->db->insert('tb_goods_sku', $s);
                        }
                    }
                    
                }
                logo_record('tb_goods',1,'新增商品',$set,1);
                $cb['status'] = 'y';
            } catch (Exception $exc) {
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
    }

    protected function changeStatus() {
        $status = $this->db->get('tb_goods', 'is_new', array('tbid' => $_POST['id']));
        if ($status == 1) {
            $rs = $this->db->update('tb_goods', array('is_new' => 0), array('tbid' => $_POST['id']));
        } else {
            $rs = $this->db->update('tb_goods', array('is_new' => 1), array('tbid' => $_POST['id']));
        }
        if ($rs > 0) {
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }

    protected function getCategory() {
        $category = $this->db->select('tb_goods_category', '*');
        $cb       = $this->getAllCategory(0, $category);
        echo json_encode_ex($cb);
    }

    protected function upload() {
        include('inc/Uploader.class.php');
        $config = array(
            'pathFormat' => 'uploads/goods/{time}{rand:6}', //保存路径
            'allowFiles' => array('.png', '.jpg', '.jpeg', '.gif'), //文件允许格式
            'maxSize'    => 2097152 //文件大小限制，单位B，2M
        );
        $up     = new \Uploader('file', $config);
        $info   = $up->getFileInfo();
        //创建缩略图
        if ($info['state'] == 'SUCCESS') {
            $url                  = explode('/', $info['url']);
            $url[count($url) - 1] = 's_' . $url[count($url) - 1];
            $url                  = implode('/', $url);
            imgsresize($info['url'], 400, 400, $url);
        }
        echo '{"url":"' . $info['url'] . '","fileType":"' . $info['type'] . '","original":"' . $info['original'] . '","preview":"/' . $info['url'] . '","state":"' . $info['state'] . '"}';
    }

    private function getAllCategory($parent_category_id, $category) {
        foreach ($category as $v) {
            if ($v['category_id'] == $parent_category_id) {
                $tmp['id']    = $v['tbid'];
                $tmp['text']  = $v['name'];
                $tmp['nodes'] = $this->getAllCategory($v['tbid'], $category);
                $cb[]         = $tmp;
            }
        }
        return $cb;
    }

    protected function delete() {
        $rs = $this->db->delete('tb_goods', array('tbid' => $_POST['id']));
        if ($rs > 0) {
            logo_record('tb_goods',1,'删除商品',$set,1);
            $cb['status'] = 'y';
        } else {
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }

    protected function getAllProperty() {
        $property = $this->db->get('tb_goods_category', 'sku', array(
            'tbid' => $_GET['id']
        ));
        echo $property;
    }

    protected function getProperty() {
        $goods = $this->db->get('tb_goods', '*', array('tbid' => $_GET['id']));
        if ($goods['skuattribute']) {
            foreach (json_decode($goods['skuattribute'], 1) as $prop) {
                $temp['name']  = $prop['name'];
                $temp['item']  = $prop['attributevalue'];
                $cb['names'][] = $temp;
            }
        }
        $sku = $this->db->select('tb_goods_sku', '*', array('goods_id' => $_GET['id']));
        foreach ($sku as $attr) {
            $temp['text']          = $attr['sku'];
            $temp['originalprice'] = $attr['originalprice'];
            $temp['price']         = $attr['price'];
            $temp['stock']         = $attr['stock'];
            $temp['code']          = $attr['code'];
            $cb['data'][]          = $temp;
        }
        echo json_encode_ex($cb);
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

    protected function resources_edit() {
        $cb['status'] ='n';
        if(!empty($_POST['name']) && !empty($_POST['file']) && !empty($_POST['size'])){
            $set['name']          = stripslashes($_POST['name']);
            $set['file']          = stripslashes($_POST['file']);
            $set['edittime']      = date('Y-m-d H:i:s');
            $set['size']          = stripslashes($_POST['size']);
            $set['file_type']     = stripslashes($_POST['file_type']);
            $set['resource_type'] = stripslashes($_POST['resource_type']);
            $set['resource_id']   = stripslashes($_POST['resource_id']);
            $set['count_virtual'] = stripslashes($_POST['count_virtual']);
            if (!empty($_POST['id1'])) {
                $rs           = $this->db->update('tb_mannual', $set, ['tbid' => $_POST['id1']]);
                $cb['status'] = $rs > 0 ? 'y' : 'n';
            } else {
                $set['createtime'] = date('Y-m-d H:i:s');
                $rs                = $this->db->insert('tb_mannual', $set);
                $cb['status']      = $rs > 0 ? 'y' : 'n';
            }
        }
        if(!empty($_POST['name_1']) && !empty($_POST['file_1']) && !empty($_POST['size_1'])){
            $set1['name']          = stripslashes($_POST['name_1']);
            $set1['file']          = stripslashes($_POST['file_1']);
            $set1['edittime']      = date('Y-m-d H:i:s');
            $set1['size']          = stripslashes($_POST['size_1']);
            $set1['file_type']     = stripslashes($_POST['file_type_1']);
            $set1['resource_type'] = stripslashes($_POST['resource_type']);
            $set1['resource_id']   = stripslashes($_POST['resource_id']);
            $set1['count_virtual'] = stripslashes($_POST['count_virtual_1']);
            if (!empty($_POST['id2'])) {
                $rs           = $this->db->update('tb_mannual', $set1, ['tbid' => $_POST['id2']]);
                $cb['status'] = $rs > 0 ? 'y' : 'n';
            } else {
                $set1['createtime'] = date('Y-m-d H:i:s');
                $rs                = $this->db->insert('tb_mannual', $set1);
                $cb['status']      = $rs > 0 ? 'y' : 'n';
            }
        }
        
        echo json_encode($cb);
    }


}
