<?php
/**
 * 实例化阿里云oos
 * @return object 实例化得到的对象
 */
function newOss()
{
    global $_ALIYOSS_CONFIG;
    include_once 'lib/Vendor/Alioss/autoload.php';
    $oss = new \OSS\OssClient(ALIOSSKEYID, ALIOSSKEYSECRET, ALIOSSENDPOINT);
    return $oss;
}

/**********************
一个简单的目录递归函数
第一种实现办法：用dir返回对象
 ***********************/
function tree($directory)
{
    $mydir = dir($directory);
    echo "<ul>\n";
    while ($file = $mydir->read()) {
        if ((is_dir("$directory/$file")) and ($file != ".") and ($file != "..")) {
            echo "<li><font color=\"#ff00cc\"><b>$file</b></font></li>\n";
            tree("$directory/$file");
        } else {
            echo "<li>$file</li>\n";
        }

    }
    echo "</ul>\n";
    $mydir->close();
}

/***********************
第二种实现办法：用readdir()函数
 ************************/
function listDir($dir)
{
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ((is_dir($dir . "/" . $file)) && $file != "." && $file != "..") {
                    echo "<b><font color='red'>文件名：</font></b>", $file, "<br><hr>";
                    listDir($dir . "/" . $file . "/");
                } else {
                    if ($file != "." && $file != "..") {
                        echo $file . "<br>";
                    }
                }
            }
            closedir($dh);
        }
    }
}
/**
 * 上传文件到oss并删除本地文件
 * @param  string $path 文件路径
 * @return bollear      是否上传
 */
function ossUpload($path)
{
    // 获取bucket名称
    $bucket = ALIOSSKEYBUCKET;
    // 先统一去除左侧的.或者/ 再添加./
    $oss_path = ltrim($path, './');
    $path = './' . $oss_path;
    //print_r($path);
    //var_dump(file_exists($path));
    if (file_exists($path)) {
        // 实例化oss类
        $oss = newOss();
        // 上传到oss

        $oss->uploadFile($bucket, $oss_path, $path);
        // 如需上传到oss后 自动删除本地的文件 则删除下面的注释
        unlink($path);
        $url = explode('/', $path);
        $url[count($url) - 1] = 's_' . $url[count($url) - 1];
        $url = implode('/', $url);
        if (file_exists($path)) {
            unlink($url);
        }
        return true;
    }
    return false;
}

/**
 * 获取完整网络连接
 * @param  string $path 文件路径
 * @return string       http连接
 */
function getUrl($path)
{
    // 如果是空；返回空
    if (empty($path)) {
        return '';
    }
    // 如果已经有http直接返回
    if (strpos($path, 'http://') !== false) {
        return $path;
    }
    // 判断是否使用了oss
    if (!defined(ALIOSSKEYID)) {
        return 'http://' . $_SERVER['HTTP_HOST'] . $path;
    } else {
        return 'http://' . ALIOSSKEYENDURL . '/' . $path;
    }

}
