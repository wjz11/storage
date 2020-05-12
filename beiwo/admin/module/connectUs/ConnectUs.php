<?php
namespace admin\connectUs;
use inc\BaseAjax;

class ConnectUs extends BaseAjax
{
    
    /**
     * 编辑联系我们我们
     * @Author : bapijun

     */
    protected function edit()
    {
        unset($_POST['id']);
        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;
        }

        $id = $this->db->get('tb_connect_us','*');
        if($id){
            $this->db->update('tb_connect_us', $set);
            $cb['status'] = 'y';
        }else{
          
            $res = $this->db->insert('tb_connect_us', $set);
            if($res){
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);

    }
 
    
}
