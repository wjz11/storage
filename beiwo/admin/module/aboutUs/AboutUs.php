<?php
namespace admin\aboutUs;
use inc\BaseAjax;

class AboutUs extends BaseAjax
{
    
    /**
     * 编辑联系我们我们
     * @Author : bapijun

     */
    protected function edit()
    {
        $set = [
            'intro' => $_POST['intro'],
            'set_up_time' => $_POST['set_up_time'],
            'pro_count' => $_POST['pro_count'],
            'provinces_count' => $_POST['provinces_count'],
            'year_count' => $_POST['year_count'],
            'patent_for_invention' => $_POST['patent_for_invention'],
            'new_utility_patents' => $_POST['new_utility_patents'],
            'national_standard' => $_POST['national_standard'],
            'Industry_technology' => $_POST['Industry_technology'],
            'course' => $_POST['course'],
            'content' => stripslashes($_POST['content']),
        ];
        $set_category = [
            'name' => $_POST['name'],
            'english_name' => $_POST['english_name'],
            'image' => $_POST['image'],
            'content' => stripslashes($_POST['content_category']),
        ];
        $id = $this->db->get('tb_about_us','*');
        if($id){
            $this->db->update('tb_about_us', $set);
            $this->db->update('tb_about_us_category', $set_category);
            logo_record('tb_about_us',1,'编辑关于倍沃',$set,1);
            $cb['status'] = 'y';
        }else{
            $res = $this->db->insert('tb_about_us', $set);
            $res1 = $this->db->insert('tb_about_us_category', $set_category);
            if($res && $res1){
                logo_record('tb_about_us',1,'新增关于倍沃',$set,1);
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);

    }
 
    
}
