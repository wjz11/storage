<?php
namespace admin\WxProduct;
include('libs/PHPExcel.php');
include('libs/PHPExcel/IOFactory.php');

class Download extends WxProduct
{

    public function downloadExcel()
    {

        $objPHPExcel = new \PHPExcel();
        // Set properties  设置文件属性
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        //set width  设置表格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

        //设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// 序号、教练名称、手机号、所属公司、任主教班级数、任主教总课时、课时课程类型分布（展示上课课程类型的节数和点到的人数）、任助教班级数、任助教总课时、助教课时课程类型分布（展示上课课程类型的节数和点到的人数）
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '名称')
            ->setCellValue('B1', '货号')
            ->setCellValue('C1', '链接地址')
            // ->setCellValue('D1', '所属公司')
            // ->setCellValue('E1', '任主教班级数')
            // ->setCellValue('F1', '任主教总课时')
            // ->setCellValue('G1', '课时课程类型分布')
            // ->setCellValue('H1', '任助教班级数')
            // ->setCellValue('I1', '任助教总课时')
            // ->setCellValue('J1', '助教课时课程类型分布')
        ;
        $type = '微信端产品详情';
        global $db; // protected  无法穿越两层的子类被继承
        
        $result=array();
        $where['AND']['item_number[!]'] = "";
        $where['AND']['name[!]'] = "";
        //$where['AND']['goods_category_id'] = 23;
        $where['ORDER']                    = array('indexid' => 'ASC', 'tbid' => 'DESC');
        $echo['rows']                      = array();
        $row = $db->select('tb_goods', ['name','item_number','tbid'], $where);
        foreach ($row as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.($key+2), $value['name'])
                ->setCellValueExplicit('B'.($key+2), $value['item_number'])
                ->setCellValueExplicit('C'.($key+2), 'http://beiwo.biomiga.com.cn/h5/pro_xq.html?xId='.$value['tbid']);
            // $tmp['url'] = 'http://beiwo.biomiga.com.cn/h5/pro_xq.html?xId='.$value;
            // $echo['rows'][] = $tmp;
            // unset($tmp);
        }
      
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  
        header("Content-Disposition: attachment;filename=".$type."导出-".date("Y-m-d").".xlsx");  
        header('Cache-Control: max-age=0');  
        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');  
        $objWriter->save( 'php://output');  

        
        exit;
    }
    private function SaveViaTempFile($objWriter){
        $filePath = ''.rand(0,getrandmax()).rand(0,getrandmax()). ".tmp";
        $objWriter->save($filePath);
        readfile($filePath);
        unlink($filePath);
    }
}



