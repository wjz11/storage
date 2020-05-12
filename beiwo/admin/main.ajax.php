<?php
require('../global.php');

switch($_REQUEST['ac']){
    case 'data_info':
        switch($_GET['type']){
            case 1:
                $echo['this_title'] = '今天';
                $echo['that_title'] = '昨天';
                $sqlwhere1 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-m-d'),
                        'createtime[<]' => date('Y-m-d', strtotime('+1 day'))
                    )
                );
                $sqlwhere2 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-m-d', strtotime('-1 day')),
                        'createtime[<]' => date('Y-m-d')
                    )
                );
                break;
            case 2:
                $echo['this_title'] = '本周';
                $echo['that_title'] = '上周';
                $sqlwhere1 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-m-d', strtotime('this week')),
                        'createtime[<]' => date('Y-m-d', strtotime('this week +6 day'))
                    )
                );
                $sqlwhere2 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-m-d', strtotime('this week -7 day')),
                        'createtime[<]' => date('Y-m-d', strtotime('this week -1 day'))
                    )
                );
                break;
            case 3:
                $echo['this_title'] = '本月';
                $echo['that_title'] = '上月';
                $sqlwhere1 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-m-01'),
                        'createtime[<]' => date('Y-m-d', strtotime(date('Y-m-01').' +1 month -1 day'))
                    )
                );
                $sqlwhere2 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-m-01', strtotime('-1 month')),
                        'createtime[<]' => date('Y-m-d', strtotime(date('Y-m-01', strtotime('-1 month')).' +1 month -1 day'))
                    )
                );
                break;
            case 4:
                $echo['this_title'] = '今年';
                $echo['that_title'] = '去年';
                $sqlwhere1 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-01-01'),
                        'createtime[<]' => date('Y-12-31')
                    )
                );
                $sqlwhere2 = array(
                    'AND' => array(
                        'createtime[>=]' => date('Y-01-01', strtotime('-1 year')),
                        'createtime[<]' => date('Y-12-31', strtotime('-1 year'))
                    )
                );
                break;
        }
        //注册量
        $echo['regist'][] = $db->count('tb_member', $sqlwhere1);
        $echo['regist'][] = $db->count('tb_member', $sqlwhere2);
        if($echo['regist'][0] == 0){
            $echo['regist'][] = $echo['regist'][1] * -100;
        }else if($echo['regist'][1] == 0){
            $echo['regist'][] = $echo['regist'][0] * 100;
        }else{
            $echo['regist'][] = round(($echo['regist'][0] - $echo['regist'][1]) / $echo['regist'][1] * 100, 2);
        }
        //访问量
        $echo['pv'][] = $db->count('tb_member_login_log', $sqlwhere1);
        $echo['pv'][] = $db->count('tb_member_login_log', $sqlwhere2);
        if($echo['pv'][0] == 0){
            $echo['pv'][] = $echo['pv'][1] * -100;
        }else if($echo['pv'][1] == 0){
            $echo['pv'][] = $echo['pv'][0] * 100;
        }else{
            $echo['pv'][] = round(($echo['pv'][0] - $echo['pv'][1]) / $echo['pv'][1] * 100, 2);
        }
        //访客数
        $sqlwhere1['GROUP'] = 'member_id';
        $sqlwhere2['GROUP'] = 'member_id';
        $echo['uv'][] = $db->count('tb_member_login_log', $sqlwhere1);
        $echo['uv'][] = $db->count('tb_member_login_log', $sqlwhere2);
        if($echo['uv'][0] == 0){
            $echo['uv'][] = $echo['uv'][1] * -100;
        }else if($echo['uv'][1] == 0){
            $echo['uv'][] = $echo['uv'][0] * 100;
        }else{
            $echo['uv'][] = round(($echo['uv'][0] - $echo['uv'][1]) / $echo['uv'][1] * 100, 2);
        }
        echo json_encode($echo);
        break;
    case 'echarts_member_sex':
        $echo[] = array(
            'value' => $db->count('tb_member', array('sex' => 1)),
            'name' => '男'
        );
        $echo[] = array(
            'value' => $db->count('tb_member', array('sex' => 2)),
            'name' => '女'
        );
        $echo[] = array(
            'value' => $db->count('tb_member', array('sex' => 3)),
            'name' => '未知'
        );
        echo json_encode($echo);
        break;
    case 'echarts_member_age':
        $data = $db->query('select count(*) from tb_member where TIMESTAMPDIFF(YEAR, birth, CURDATE()) <= 18')->fetch();
        $echo[] = $data[0];
        $data = $db->query('select count(*) from tb_member where TIMESTAMPDIFF(YEAR, birth, CURDATE()) > 18 and TIMESTAMPDIFF(YEAR, birth, CURDATE()) <= 24')->fetch();
        $echo[] = $data[0];
        $data = $db->query('select count(*) from tb_member where TIMESTAMPDIFF(YEAR, birth, CURDATE()) > 24 and TIMESTAMPDIFF(YEAR, birth, CURDATE()) <= 30')->fetch();
        $echo[] = $data[0];
        $data = $db->query('select count(*) from tb_member where TIMESTAMPDIFF(YEAR, birth, CURDATE()) > 30 and TIMESTAMPDIFF(YEAR, birth, CURDATE()) <= 36')->fetch();
        $echo[] = $data[0];
        $data = $db->query('select count(*) from tb_member where TIMESTAMPDIFF(YEAR, birth, CURDATE()) > 36 and TIMESTAMPDIFF(YEAR, birth, CURDATE()) <= 42')->fetch();
        $echo[] = $data[0];
        $data = $db->query('select count(*) from tb_member where TIMESTAMPDIFF(YEAR, birth, CURDATE()) > 42')->fetch();
        $echo[] = $data[0];
        echo json_encode($echo);
        break;
}
?>
