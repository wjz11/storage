<?php
namespace admin\set;
use inc\BaseAjax;
require('../../../global.php');

class ajax extends BaseAjax
{
    
    /**
     * 编辑关于我们
     * @Author : bapijun

     * echo 是否编辑成功
     */
    protected function edit()
    {
        
        $set = array(
           
            'registration' => stripslashes($_POST['registration']),
           
        );
        $id = $this->db->get('tb_set','*');
        if($id){
            $this->db->update('tb_set', $set);
            $cb['status'] = 'y';
        }else{
          
            $res = $this->db->insert('tb_set', $set);
            if($res){
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);

    }
 
    
}

$ajax = new ajax();
$ajax->dealWithAjax();
