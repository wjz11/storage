<?php
/**
 * 对变量进行 JSON 编码
 * @param mixed value 待编码的 value ，除了resource 类型之外，可以为任何数据类型，该函数只能接受 UTF-8 编码的数据
 * @return string 返回 value 值的 JSON 形式
 */
function jsonEncodeEx($value)
{
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        $str = json_encode($value);
        $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($matchs) {
            return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
        }, $str);
        return $str;
    } else {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

//api加载class并初始化
function includeclass($api, $class)
{
    $api->includefile($api->infodir . "/" . $class . ".class.php");
    return $$class = new $class($api);
}
//获得头像图片
function getheadimg($headimg)
{
    if (empty($headimg)) {
        $headimg = AVATAR;
    }
    if (defined('ALIOSSKEYENDURL')) {
        $site = 'http://' . ALIOSSKEYENDURL . '/';
    } else {
        $site = SITEROOTURL;
    }
    return $site . $headimg;
}
//解析图片
function getimg($img, $mode = 0, $smode = 0)
{
    $img = explode(FILESPLIT, $img);
    foreach ($img as &$value) {
        if (!empty($value)) {
            if (substr($value, 0, 4) != 'http') {
                if ($smode == 1) {
                    $value = explode('/', $value);
                    $value[count($value) - 1] = 's_' . $value[count($value) - 1];
                    $value = implode('/', $value);
                }
                if (defined('ALIOSSKEYENDURL')) {
                    $site = 'http://' . ALIOSSKEYENDURL . '/';
                } else {
                    $site = SITEROOTURL;
                }
                $value = $site . $value;
            }
        }
    }
    unset($value);
    if ($mode == 1) {
        return $img[0];
    } else {
        if (empty($img[0]) && count($img) == 1) {
            $img = null;
        }
        return $img;
    }
}
//创建文件目录
function create($dir)
{
    if (!is_dir($dir)) {
        $temp = explode('/', $dir);
        $cur_dir = '';
        for ($i = 0; $i < count($temp); $i++) {
            $cur_dir .= $temp[$i] . '/';
            if (!is_dir($cur_dir)) {
                @mkdir($cur_dir, 0777);
            }
        }
    }
}
function getfilekey($keys, $file, $name = '', $level = 0)
{
    $key = array_shift($keys);
    if (count($keys) != 0) {
        $return = getfilekey($keys, $file[$key], $name, $level + 1);
    } else {
        $return[$name] = $file[$key];
    }
    return $return;
}
//图片上传
function creatfilesimg($files, $userid, $dirname, $key = '-', $simgh = 220, $simgw = 220, $maxfilesize = 0)
{
    $return = '';
    if (is_array($files['name'])) {
        $file = array();
        if ($key != '-') {
            $filelist = array();
            $key = explode(',', $key);
            $tempkey = '';
            foreach ($files as $keys => $column) {
                $filelist = array_merge($filelist, getfilekey($key, $column, $keys));
            }
            $return = creatfilesimg($filelist, $userid, $dirname, '-', $simgh, $simgw, $maxfilesize);
        } else {
            foreach ($files['name'] as $key => $name) {
                $file['name'] = $files['name'][$key];
                $file['type'] = $files['type'][$key];
                $file['tmp_name'] = $files['tmp_name'][$key];
                $file['error'] = $files['error'][$key];
                $file['size'] = $files['size'][$key];
                $return .= creatfilesimg($file, $userid, $dirname, $key, $simgh, $simgw, $maxfilesize) . FILESPLIT;
            }
            $return = substr($return, 0, strlen($return) - strlen(FILESPLIT));
        }
    } else {
        $name = $files['name'];
        $file_ext = strtolower(substr($name, strrpos($name, ".") + 1, strlen($name)));
        if (is_uploaded_file($files["tmp_name"]) && $files["error"] == 0 && $file_ext != '' && $file_ext = checkfiletype($files["tmp_name"], $file_ext)) {
            $file_size = @filesize($files["tmp_name"]);
            if ($maxfilesize == 0) {
                $maxfilesize = MAXFILESIZE * 1024 * 1024;
            }
            if ($file_size <= 0 || $file_size > $maxfilesize) {
                return false;
            }
            $upload_path = DOFILESPATH . $dirname . '/' . $userid . '/';
            //建立文件夹
            create($upload_path);
            $upload_file['name'] = md5(uniqid(rand()));
            $upload_file['smallname'] = $upload_path . "s_" . $upload_file['name'] . '.' . $file_ext;
            $upload_file['filename'] = $upload_path . $upload_file['name'] . '.' . $file_ext;
            $new_img = $upload_path . $upload_file['name'] . '.' . $file_ext;
            if (@move_uploaded_file($files["tmp_name"], $upload_file["filename"])) {
                if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "gif" || $file_ext == "png" || $file_ext == "bmp") {

                    //处理exif上传图片旋转问题
                    $imgInfo = getimagesize($upload_file['filename']);
                    if (2 == $imgInfo[2]) {
                        // var_dump($imgInfo[2]);die;
                        $img2 = imagecreatefromjpeg($upload_file["filename"]);
                        @$exif = exif_read_data($upload_file["filename"]);
                        if (!empty($exif['Orientation'])) {
                            switch ($exif['Orientation']) {
                                case 8:
                                    $img2 = imagerotate($img2, 90, 0);
                                    imagejpeg($img2, $new_img);
                                    break;
                                case 3:
                                    $img2 = imagerotate($img2, 180, 0);
                                    imagejpeg($img2, $new_img);
                                    break;
                                case 6:
                                    $img2 = imagerotate($img2, -90, 0);
                                    imagejpeg($img2, $new_img);
                                    break;
                            }
                        }
                        imagedestroy($img2);
                        // unlink($upload_file['filename']);
                    }
                    $out = imgsresize($new_img, intval($simgw), intval($simgh), $upload_file['smallname']);
                    if (defined('ALIOSSKEYID')) {
                        oss_upload($new_img);
                    }

                }
                if (defined('ALIOSSKEYID')) {
                    oss_upload($new_img);
                }
                $return .= $new_img;
            }
        }
    }
    return $return;
}

//验证图片类型
function checkfiletype($f, $ftype)
{
    $accept = array("jpg", "png", "gif", "bmp");
    if ($ftype == "txt") {
        $fileType = 'txt';
    } else {
        $file = fopen($f, "rb");
        $bin = fread($file, 4); //只读2字节
        $typeCode = "";
        $strInfo = @unpack("C4chars", $bin);
        for ($i = 1; $i <= 4; $i++) {
            $strInfo['chars' . $i] = dechex($strInfo['chars' . $i]);
            if (strlen($strInfo['chars' . $i]) == 1) {
                $strInfo['chars' . $i] = "0" . $strInfo['chars' . $i];
            }
            $typeCode = $typeCode . $strInfo['chars' . $i];
        }
        $fileType = '';
        if ($typeCode == "504b0304") {
            $file = fopen($f, "rb");
            fseek($file, 4);
            $bin = fread($file, 1);
            $strInfo = @unpack("C1chars", $bin);
            $strInfo['chars'] = dechex($strInfo['chars']);
            if (strlen($strInfo['chars']) == 1) {
                $strInfo['chars'] = "0" . $strInfo['chars'];
            }
            $typeCode = $typeCode . $strInfo['chars'];
            switch ($typeCode) {
                case '504b030414':$fileType = 'docx';
                    break; /*docx/pptx/xlsx*/
                case '504b03040a':$fileType = 'zip';
                    break;
            }
        } else {
            switch ($typeCode) {
                case 'd0cf11e0':$fileType = 'doc';
                    break; /*doc/ppt/xls/pot/pps/vsd/wps/dps/et*/
                case '7b5c7274':$fileType = 'rtf';
                    break;
                case '25504446':$fileType = 'pdf';
                    break;
                case 'ffd8ffe1':$fileType = 'jpg';
                    break;
                case 'ffd8ffe0':$fileType = 'jpg';
                    break;
                case '52494646':$fileType = 'jpg';
                    break;
                case '89504e47':$fileType = 'png';
                    break;
                case '38425053':$fileType = 'psd';
                    break;
                case '424d3803':$fileType = 'bmp';
                    break;
                case '47494638':$fileType = 'gif';
                    break;
                default:$fileType = 'wrong';
            }
        }
        fclose($file);
    }
    if (!in_array($fileType, $accept)) {
        return false;
    } elseif ($fileType == "doc") {
        if ($ftype != "doc" && $ftype != "ppt" && $ftype != "xls" && $ftype != "pot" && $ftype != "pps" && $ftype != "vsd" && $ftype != "wps" && $ftype != "dps" && $ftype != "et") {
            return false;
        }
    } elseif ($fileType == "docx") {
        if ($ftype != "docx" && $ftype != "pptx" && $ftype != "xlsx") {
            return false;
        }
    }
    return $fileType;
}
//生成缩略图
function imgsresize($img, $w, $h, $newfilename)
{
    //Check if GD extension is loaded
    if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }

    //Get Image size info
    $imgInfo = getimagesize($img);
    switch ($imgInfo[2]) {
        case 1:$im = imagecreatefromgif($img);
            break;
        case 2:$im = imagecreatefromjpeg($img);
            break;
        case 3:$im = imagecreatefrompng($img);
            break;
        default:trigger_error('Unsupported filetype!', E_USER_WARNING);
            break;
    }

    //If image dimension is smaller, do not resize
    if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
    } else {
        //yeah, resize it, but keep it proportional
        if ($w / $imgInfo[0] > $h / $imgInfo[1]) {
            $nWidth = $w;
            $nHeight = $imgInfo[1] * ($w / $imgInfo[0]);
        } else {
            $nWidth = $imgInfo[0] * ($h / $imgInfo[1]);
            $nHeight = $h;
        }
    }
    $nWidth = round($nWidth);
    $nHeight = round($nHeight);

    $newImg = imagecreatetruecolor($nWidth, $nHeight);

    /* Check if this image is PNG or GIF, then set if Transparent*/
    if ($imgInfo[2] == 1) {
        imagealphablending($newImg, false);
        $white = imagecolorallocate($newImg, 0xFF, 0xFF, 0xFF);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $white);
        imagecolortransparent($newImg, $white);
    } else if ($imgInfo[2] == 3) {
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
        imagecolortransparent($newImg, $transparent);
    }
    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
    //Generate the file, and rename it to $newfilename
    switch ($imgInfo[2]) {
        case 1:imagegif($newImg, $newfilename);
            break;
        case 2:imagejpeg($newImg, $newfilename);
            break;
        case 3:imagepng($newImg, $newfilename);
            break;
        default:trigger_error('Failed resize image!', E_USER_WARNING);
            break;
    }
    imageDestroy($newImg);
    return $imgInfo[2];
}
//创建唯一编号
function getordernum($pre, $end = '')
{
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P');
    $orderSn = $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $pre . $orderSn . $end;
}
//远程获取url数据
function geturlcontent($dataurl, $method = 'GET', $data = '', $header = '')
{
    if (!empty($data) && $method == 'GET') {
        if (strpos($dataurl, '?') == false) {
            $dataurl .= '?';
        } else {
            $dataurl .= '&';
        }
        $dataurl .= http_build_query($data);
    }
    $ch = curl_init(); //初始化curl

    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_URL, $dataurl); //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $databack = curl_exec($ch); //运行curl
    curl_close($ch);
    return $databack;
}
//输出html
function htmldetail($content)
{
    return '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"><title>' . $goods['title'] . '</title><style>body{margin:0;padding:0}p{margin:0}img{display:block;margin:0 auto;vertical-align:top;max-width:100%}</style></head><body>' . $content . '</body></html>';
}
//发送邮件
function sendmail($address, $subject, $body)
{
    require 'lib/phpmailer/PHPMailerAutoload.php';
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = MAILHOST; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = MAILNAME; // SMTP username
    $mail->Password = MAILPWD; // SMTP password
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25; // TCP port to connect to

    $mail->setFrom(MAILNAME, PLATFORM);
    foreach ($address as $value) {
        if (!is_array($value)) {
            $mail->addAddress($value); // Add a recipient
        } else {
            $mail->addAddress($value[0], $value[1]); // Add a recipient
        }
    }
    //邮件回复地址
    //$mail->addReplyTo('info@example.com', 'Information');
    //抄送
    //$mail->addCC('cc@example.com');
    //暗抄送
    //$mail->addBCC('bcc@example.com');
    //添加附件
    /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML*/

    $mail->Subject = $subject;
    $mail->Body = $body;
    //不支持html的邮件展示
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail = $mail->send();
    $return = array();
    if (!$mail) {
        $return['state'] = true;
        $return['errorinfo'] = $mail->ErrorInfo;
    } else {
        $return['state'] = false;
    }
    return $return;
}
function dateformat($data)
{
    $diftime = time() - strtotime($data);
    $day = floor($diftime / (24 * 60 * 60));
    if ($day == 0) {
        $return = '一天内';
    } elseif ($day <= 30) {
        $return = tochinasenum($day) . '天前';
    } else {
        $return = '一个月前';
    }
    return $return;
}
function tochinasenum($num)
{
    $char = array("零", "一", "二", "三", "四", "五", "六", "七", "八", "九");
    $dw = array("", "十", "百", "千", "万", "亿", "兆");
    $retval = "";
    $proZero = false;
    for ($i = 0; $i < strlen($num); $i++) {
        if ($i > 0) {
            $temp = (int) (($num % pow(10, $i + 1)) / pow(10, $i));
        } else {
            $temp = (int) ($num % pow(10, 1));
        }

        if ($proZero == true && $temp == 0) {
            continue;
        }

        if ($temp == 0) {
            $proZero = true;
        } else {
            $proZero = false;
        }

        if ($proZero) {
            if ($retval == "") {
                continue;
            }

            $retval = $char[$temp] . $retval;
        } else {
            $retval = $char[$temp] . $dw[$i] . $retval;
        }

    }
    if ($retval == "一十") {
        $retval = "十";
    }

    return $retval;
}
//获取内网IP，0返回IP地址，1返回IPV4地址数字
function getip($type = 1)
{
    $type = $type ? 1 : 0;
    static $ip = null;
    if ($ip !== null) {
        return $ip[$type];
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) {
            unset($arr[$pos]);
        }

        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%d", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
//获取中文拼音
function pinyin($_String, $_Code = 'utf-8')
{
    $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
        "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
        "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
        "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
        "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
        "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
        "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
        "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
        "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
        "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
        "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
        "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
        "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
        "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
        "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
        "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

    $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
        "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
        "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
        "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
        "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
        "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
        "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
        "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
        "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
        "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
        "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
        "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
        "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
        "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
        "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
        "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
        "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
        "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
        "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
        "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
        "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
        "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
        "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
        "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
        "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
        "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
        "|-10270|-10262|-10260|-10256|-10254";
    $_TDataKey = explode('|', $_DataKey);
    $_TDataValue = explode('|', $_DataValue);

    $_Data = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
    arsort($_Data);
    reset($_Data);

    if ($_Code != 'gb2312') {
        $_String = _U2_Utf8_Gb($_String);
    }
    $_Res = '';
    for ($i = 0; $i < strlen($_String); $i++) {
        $_P = ord(substr($_String, $i, 1));
        if ($_P > 160) {
            $_Q = ord(substr($_String, ++$i, 1));
            $_P = $_P * 256 + $_Q - 65536;
        }
        $_Res .= _Pinyin($_P, $_Data);
    }
    return preg_replace("/[^a-z0-9]*/", '', $_Res);
}

function _Pinyin($_Num, $_Data)
{
    if ($_Num > 0 && $_Num < 160) {
        return chr($_Num);
    } elseif ($_Num < -20319 || $_Num > -10247) {
        return '';
    } else {
        foreach ($_Data as $k => $v) {
            if ($v <= $_Num) {
                break;
            }
        }
        return $k;
    }
}

function _U2_Utf8_Gb($_C)
{
    $_String = '';
    if ($_C < 0x80) {
        $_String .= $_C;
    } elseif ($_C < 0x800) {
        $_String .= chr(0xC0 | $_C >> 6);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif ($_C < 0x10000) {
        $_String .= chr(0xE0 | $_C >> 12);
        $_String .= chr(0x80 | $_C >> 6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif ($_C < 0x200000) {
        $_String .= chr(0xF0 | $_C >> 18);
        $_String .= chr(0x80 | $_C >> 12 & 0x3F);
        $_String .= chr(0x80 | $_C >> 6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    }
    return iconv('UTF-8', 'GB2312', $_String);
}

function _Array_Combine($_Arr1, $_Arr2)
{
    for ($i = 0; $i < count($_Arr1); $i++) {
        $_Res[$_Arr1[$i]] = $_Arr2[$i];
    }
    return $_Res;
}
//预处理数据库字段
function getcolumn($table, $mode = 0, $columnname = '')
{
    global $db;
    $db->getcolumn($table, 1);
    $column = current($db->column);
    if (empty($columnname)) {
        $columnname = $table;
    }
    $out = '';
    foreach ($column as $key => $value) {
        if ($mode == 0) {
            switch ($value['type']) {
                case "varchar":$type = 'string';
                    break;
                case "text":$type = 'string';
                    break;
                case "bigint":$type = 'int';
                    break;
                case "int":$type = 'int';
                    break;
                case "tinyint":$type = 'int';
                    break;
                case "timestamp":$type = 'datetime';
                    break;
                default:$type = $value['type'];
                    break;
            }
            $list = '';
            if ($type == 'int') {
                $comment = explode(' ', $value['comment']);
                if (count($comment) > 1) {
                    $list = ',\'list\' => array(';
                    foreach ($comment as $c) {
                        if (is_numeric(mb_substr($c, 0, 1, 'utf-8'))) {
                            $list .= mb_substr($c, 0, 1, 'utf-8') . '=>\'' . mb_substr($c, 1, mb_strlen($c, 'utf-8'), 'utf-8') . '\',';
                        }
                    }
                    $list = mb_substr($list, 0, mb_strlen($list, 'utf-8') - 1, 'utf-8') . ')';
                    $value['comment'] = $comment[0];
                }
            }
            echo '$this->infoarr[\'' . str_replace('_', '', $key) . '\']=array(\'type\' => \'' . $type . '\',\'summary\' => \'' . $value['comment'] . '\'' . $list . ');<br>';
        } else if ($mode == 1) {
            $out .= '\'' . str_replace('_', '', $key) . '\',';
        } else if ($mode == 2) {
            echo '$data[\'' . str_replace('_', '', $key) . '\']=$' . $columnname . '[\'' . $key . '\'];<br>';
        } else if ($mode == 3) {
            if ($key != 'tbid' && $key != 'createtime') {
                $out .= '\'' . $key . '\'=>$param[\'' . str_replace('_', '', $key) . '\'],';
            }
        }
    }
    if ($mode == 1 || $mode == 3) {
        $out = substr($out, 0, strlen($out) - 1);
    }
    echo $out;
    exit;
}
function xml2array($contents, $get_attributes = 1)
{
    if (!$contents) {
        return array();
    }

    if (!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }
    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $contents, $xml_values);
    xml_parser_free($parser);
    if (!$xml_values) {
        return;
    }
//Hmm...
    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();
    $current = &$xml_array;
    //Go through the tags.
    foreach ($xml_values as $data) {
        unset($attributes, $value); //Remove existing values, or there will be trouble
        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data); //We could use the array by itself, but this cooler.
        $result = '';
        if ($get_attributes) {
//The second argument of the function decides this.
            $result = array();
            if (isset($value)) {
                $result['value'] = $value;
            }

            //Set the attributes too.
            if (isset($attributes)) {
                foreach ($attributes as $attr => $val) {
                    if ($get_attributes == 1) {
                        $result['attr'][$attr] = $val;
                    }
                    //Set all the attributes in a array called 'attr'
                    /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
                }
            }
        } elseif (isset($value)) {
            $result = $value;
        }
        //See tag status and do the needed.
        if ($type == "open") {
//The starting of the tag '<tag>'
            $parent[$level - 1] = &$current;
            if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                //Insert New tag
                $current[$tag] = $result;
                $current = &$current[$tag];
            } else {
                //There was another element with the same tag name
                if (isset($current[$tag][0])) {
                    array_push($current[$tag], $result);
                } else {
                    $current[$tag] = array($current[$tag], $result);
                }
                $last = count($current[$tag]) - 1;
                $current = &$current[$tag][$last];
            }
        } elseif ($type == "complete") {
            //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if (!isset($current[$tag])) {
                //New Key
                $current[$tag] = $result;
            } else {
                //If taken, put all things inside a list(array)
                if ((is_array($current[$tag]) and $get_attributes == 0) //If it is already an array...
                     or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
                    array_push($current[$tag], $result); // ...push the new element into that array.
                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                }
            }
        } elseif ($type == 'close') {
            //End of tag '</tag>'
            $current = &$parent[$level - 1];
        }
    }
    return ($xml_array);
}
// 参数解释
// $string： 明文 或 密文
// $operation：DECODE表示解密,其它表示加密
// $key： 密匙
// $expiry：密文有效期
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0, $ckey_length = 4)
{
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥\
    // 密匙
    $key = md5($key != '' ? $key : getconfig('authkey'));
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}
