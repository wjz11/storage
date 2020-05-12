<?php 
require('../global.php');
$db2 = new Medoo\Medoo(array(
    'database_type' => 'mysql',
    'database_name' => 'hangmo',
    'server' => '127.0.0.1',
    'port' => 3306,
    'username' => 'root',
    'password' => '',//eoner.com
    'charset' => 'utf8',
    'option' => array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       
    )
));


switch($_REQUEST['ac']){


    case 'download':
        $gamename = $db2->get('game', 'name', array("id"=> $_GET['gid']));
      
        $gameitemjc = $db2->select('gameitem', array('short_name','type','code'), array("game_id"=> $_GET['gid']));
      
        $downloadfilename = "参赛人员明细";
        $content[] = array(
            'xuhao' => '序号',
            'teamname' => '代表队',
            'type' => '职务',
            'name' => '姓名',
            'teamcode' => '编号',
            'sex' => '性别',
            'nation' => '民族',
            'zubie' => '组别'
        );
        foreach($gameitemjc as $mkey =>$mval){
            $content[0]['item'.$mkey]= $mval['short_name'];
            $game_type = $mval['type'];
            if(!empty($game_type) && $game_type == 2){
                $content[0]['group'.$mkey]= '('.$mval['code'].')分组';
            }
        }
        $where['AND']['game_team.game_id'] = $_GET['gid'];
        $where['AND']['game_team.isshow'] = 1;
      
        $row = $db2->select('game_team', array('id','name'), $where);
        
        //        dump($row);
        //        exit;
        //        writelog('dd',$_GET['gid']);
        //        echo 'dd';exit;
        if($row != NULL){

            $i = 1;
            //            $f = 0;
            foreach($row as $k => $v) {

                $islingdui_array = $db2->select('game_team_apply', array('name','code','nation','sex','type'), array('AND' => array("game_team_id" => $v['id'], 'type' => array(3, 4))));

                if (!empty($islingdui_array)) {
                    foreach ($islingdui_array as $k01 => $v01) {
                        $tmp0['xuhao'] = $i++;
                        $tmp0['teamname'] = $v['name'];
                        $type0 = $v01['type'] == 3 ? '领队' : '领队兼教练';
                       
                        $tmp0['name'] = !empty($v01['name']) ? $v01['name'] : '';
                 
                        $tmp0['teamcode'] = !empty($v01['code']) ? $v01['code'] : ' ';
                        $tmp0['sex'] = $v01['sex'] == 1 ? '男' : '女';
            
                        $tmp0['nation'] = !empty($v01['nation']) ? $v01['nation'] : '';
                        $tmp0['zubie'] = '';
                       
                       /*  foreach ($gameitemjc as $mkey => $mval) {
                            $tmp0['item' . $mkey] = '';
                            $game_type = $db2->get('gameitem', 'type', array('AND' => array('game_id' => $_GET['gid'], 'code' => $mval)));
                            if (!empty($game_type) && $game_type == 2) {
                                $tmp0['group' . $mkey] = '';
                            }
                        } */
                        $content[] = $tmp0;
                        unset($tmp0);
                    }
                }
             
                $teamappl2 = $db2->select('game_team_apply', array('name','code','nation','sex'), array('AND' => array("game_team_id" => $v['id'], "type" => 2)));
                //                dump($db2->last_query());exit;
                $tmp = array();
                if ($teamappl2 != NULL) {
                    foreach ($teamappl2 as $k10 => $vb2) {
                        //                        $tmp['xuhao'] = ($k1+1);
                        //                        if ($vb2['phone'] != $v['phone']) {
                        $tmp2['xuhao'] = $i++;
                        $tmp2['teamname'] = $v['name'];
                        $type = '教练';
                        $tmp2['type'] = $type;
                        $tmp2['name'] = $vb2['name'];
                        $teamcode = $vb2['code'];
                       
                        $tmp2['teamcode'] = !empty($teamcode) ? $teamcode : ' ';
                        //                            $tmp2['teamcode'] = ' ';
                        $tmp2['sex'] = $vb2['sex'] == 1 ? '男' : '女';
                        $tmp2['nation'] = $vb2['nation'];
                        //                        $vb['member_id'] = 206;
                        $item_id_array = $db2->select('gameitem', 'id', array('game_id' => $_GET['gid']));

                        if (!empty($item_id_array)) {
                            //获取组别
                            $tmp2['zubie'] = '';
                           
                            $item_short_name = '';//表内容中 项目的项目名称
                            //                            dump($item_id_array);
                           /*  foreach ($item_id_array as $k2 => $v2) {
                                
                                $tmp2['item' . $k2] = '';
                                $game_type = $db2->get('gameitem', 'type', array('AND' => array('id' => $v2)));
                                if (!empty($game_type) && $game_type == 2) {
                                    
                                    $tmp2['group' . $k2] = '';
                                }
                            } */
                            
                        }

                        $content[] = $tmp2;
                        unset($tmp2);
                        //                        }
                    }
                }
             
                //                dump($content);exit;
                //运动员排序
                $teamappl = $db2->select('game_team_apply', array('name','code','nation','sex'), array('AND' => array("game_team_id" => $v['id'], "type" => "1")));
                if ($teamappl != NULL) {
                    foreach ($teamappl as $k1 => $vb) {
                        //                        $tmp['xuhao'] = ($k1+1);
                        $tmp['xuhao'] = $i++;
                        $tmp['teamname'] = $v['name'];
                        $type = '运动员';
                        $tmp['type'] = $type;
                        $tmp['name'] = $vb['name'];
                        $teamcode = $vb['code'];
                        $tmp['teamcode'] = !empty($teamcode) ? $teamcode : ' ';
                        $tmp['sex'] = $vb['sex'] == 1 ? '男' : '女';
                        $tmp['nation'] = $vb['nation'];
                        //                        $vb['member_id'] = 206;
                        //                        $grouptypeflag = array();
                        $grouptypearray = $db2->select('game_item_eroll', 'grouptype', array('AND' => array("apply_id" => $vb['id'], "game_id" => $_GET['gid'])));
                        $grouptypeflag = $grouptypeconfig[$grouptypearray[0]];
                        $tmp['zubie'] = $grouptypeflag;
                        $item_id_array = $db2->select('gameitem', 'id', array('game_id' => $_GET['gid']));
                        var_dump($item_id_array);
                        
                        if (!empty($item_id_array)) {
                            //获取组别
                            //获取项目id 已经参加的项目 就爆出来哪个项目
                            //为关联数组 $k 从1开始
                            $item_short_name = '';//表内容中 项目的项目名称
                            //                            dump($item_id_array);
                            foreach ($item_id_array as $k3 => $v3) {
                                //                                    $ifitemname = $db2->get('game_item_eroll', '*', array('AND' => array('gameitem_id' => $v3, 'apply_id' => $vb['id'])));
                                //                                    if (!empty($ifitemname)) {
                                $ifitemname = $db2->get('game_item_eroll', 'id', array('AND' => array('gameitem_id' => $v3, 'apply_id' => $vb['id'])));
                                //                                    if ($db2->has('game_item_eroll', array('AND' => array('gameitem_id' => $v3, 'apply_id' => $vb['id'])))) {
                                if (!empty($ifitemname)) {
                                    $item_short_name = $db2->get('gameitem', 'short_name', array('id' => $v3));
                                    $tmp['item' . $k3] = $item_short_name;
                                } else {
                                    $tmp['item' . $k3] = '';
                                }
                                $game_type = $db2->get('gameitem', 'type', array('AND' => array('id' => $v3)));
                                if (!empty($game_type) && $game_type == 2) {
                                    $duiwu_groupname = $db2->get('game_item_eroll', 'groupname', array('AND' => array('gameitem_id' => $v3, 'apply_id' => $vb['id'])));
                                    $tmp['group' . $k3] = !empty($duiwu_groupname) ? $duiwu_groupname : '';
                                }

                            } 
                        } 

                        $content[] = $tmp;
                        unset($tmp);
                    }
                }


                //        ob_flush();
                //        flush();
                //                dump($content);exit;
                //                sleep(1);
                //                }
            }

        }
        //        dump($content);
        //        exit;
        //导出excel表格
       
        $downloadfilename = iconv('utf-8', 'gbk', $downloadfilename);
        //        ob_flush();
        //        flush();
        //create_xls($content,$downloadfilename);
        //        unset($content);
        break;

}


