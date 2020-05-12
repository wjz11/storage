<?php

require '../../../global.php';
set_time_limit(0);
ini_set("memory_limit", "1024M");
ini_set('display_errors', 'On');
include 'inc/Uploader.class.php';
$config = array(
    'pathFormat' => 'uploads/files/{time}{rand:6}', //保存路径
    'allowFiles' => array('.xlsx', '.xls'), //文件允许格式
    'maxSize' => 52428800, //文件大小限制，单位B,50M
    'default_socket_timeout ' => 300, //链接时长（PHP）
    'upload_max_filesize  ' => 16 * 1024, //上传文件大小限制（PHP）
);
$up = new Uploader('file', $config);
$info = $up->getFileInfo();
if ($info['state'] != 'SUCCESS')
{
    $echo['state'] = "n";
    $echo['error'] = $info['state'];
    echo json_encode($echo);
    exit;
}
include 'libs/PHPExcel.php';
include 'libs/PHPExcel/IOFactory.php';
//设定缓存模式为经gzip压缩后存入cache（还有多种方式请百度）
$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
$cacheSettings = array();
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
$filePath = $info['url'];
$fileType = PHPExcel_IOFactory::identify($filePath);
$fileReader = PHPExcel_IOFactory::createReader($fileType);
$fileReader->setReadDataOnly(true);
$fileReader->setLoadSheetsOnly(true);
$Sheets = $fileReader->load($filePath);
$Sheet = $Sheets->getSheet(0)->toArray();

$i = 0;
if ($Sheet[0][0] != "顶级分类" || $Sheet[0][1] != "二级分类" || $Sheet[0][2] != "标题" ||
        $Sheet[0][3] != "货号" || $Sheet[0][4] != "简介" || $Sheet[0][5] != "规格" ||
        $Sheet[0][6] != "特点" || $Sheet[0][7] != "产品说明" || $Sheet[0][8] != "问题与答案" ||
        $Sheet[0][9] != "注意事项" || $Sheet[0][10] != "排序")
{
    $echo['state'] = "n";
    $echo['error'] = "操作失败,请检查表头字段是否跟模板一致！";
    echo json_encode($echo);
    exit;
}
else
{
    unset($Sheet[0]);
    $chunkData = array_chunk($Sheet, 5000); // 将这个10W+的数组分割成5000一个的小数组。这样就一次批量插入5000条数据。mysql 是支持的。
    $goods_category_list = $db->select('tb_goods_category', ['tbid', 'name']);
    $gc_list = [];
    foreach ($goods_category_list as $gcl_v)
    {
        $gc_list[$gcl_v['tbid']] = $gcl_v['name'];
    }
    for ($i = 0; $i < count($chunkData); $i++)
    {
        $set = array();
        foreach ($chunkData[$i] as $k => $v)
        {
            
            $goodsisse=$db->get('tb_goods', 'tbid',array('item_number'=>$v['3']));
            if($goodsisse!=0){
                $seupt=array(
                    'name'=>$v['2'],
                    'intro'=>$v['4'],
                    'format'=>$v['5'],
                    'trait'=>$v['6'],
                    'description'=>$v['7'],
                    'question'=>$v['8'],
                    'attention'=>$v['9'],
                    'indexid'=>$v['10'],
                    'edittime'=>date('Y-m-d H:i:s')
                );
                $goodedit=$db->update('tb_goods', $seupt, array('item_number' => $v['3']));
            }else{
                if ($v[0] && $v[1])
                {
                    $tmp = [];
                    $gc_k0 = array_search($v['0'], $gc_list);
                    $gc_k1 = array_search($v['1'], $gc_list);
                    $tmp[] = $gc_k1 ? $gc_k1 : $gc_k0;
                    $tmp[] = $v['2'] ? addslashes($v['2']) : ''; #标题name
                    $tmp[] = $v['3'] ? addslashes($v['3']) : ''; #货号item_number
                    $tmp[] = $v['4'] ? addslashes($v['4']) : ''; #简介intro
                    $tmp[] = $v['5'] ? addslashes($v['5']) : ''; #规格format
                    $tmp[] = $v['6'] ? addslashes($v['6']) : ''; #特点trait
                    $tmp[] = $v['7'] ? addslashes($v['7']) : ''; #产品说明description
                    $tmp[] = $v['8'] ? addslashes($v['8']) : ''; #问题与答案question
                    $tmp[] = $v['9'] ? addslashes($v['9']) : ''; #注意事项attention
                    $tmp[] = $v['10'] ? addslashes($v['10']) : ''; #排序indexid
                    $tmp[] = date('Y-m-d H:i:s'); #创建时间createtime
                    $tmp[] = date('Y-m-d H:i:s'); #编辑时间edittime
                    
                    if ($_POST['radioValue'] == 1)
                    {
                        
                        if ($gc_k1 == $_POST['cid'])
                        {
                            $set[] = "('" . implode("','", $tmp) . "')";
                        }
                    }
                    else
                    {
                        $set[] = "('" . implode("','", $tmp) . "')";
                    }
                }
            }
        }
        if (is_array($set))
        {
            $set_do = implode(",", $set);
        }
        else
        {
            $set_do = $set;
        }
        
        if (!empty($set_do))
        {
            $sql = "INSERT INTO `tb_goods` (`goods_category_id`,`name`,`item_number`,`intro`,`format`,`trait`,`description`,`question`,`attention`,`indexid`,`createtime`,`edittime`) VALUES " . $set_do;
            $db->query($sql);
            unset($set);
        }
    }
    $echo['error'] = '成功';
    $echo['state'] = "SUCCESS";
    echo json_encode($echo);
    exit;
}
