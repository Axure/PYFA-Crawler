<?php
/**
 * Created by PhpStorm.
 * User: zhenghu
 * Date: 14 年 七月. 24.
 * Time: 15:28
 */

function fuckRenren($url) {
    echo "肏你妈";

    $url = "http://www.renren.com/318606336/profile";

    $content = file_get_contents($url);

    print_r($content);

    $js_pattern = "/http.*\.js/";

    preg_match_all($js_pattern, $content, $matches);
    print_r($matches[0]);

    foreach ($matches[0] as $js_address) {
        $js_content = file_get_contents($js_address);
//    print_r($js_content);
        preg_match_all("/.*more.*/", $js_content, $js_matches);
        print_r($js_matches[0]);

    }
}




function findMajor($url) {
    $url[0] = "http://gaokao.eol.cn/zyjs_2924/20130511/t20130511_941400";
    for ($i = 1; $i < 10; $i++) {
        $url[$i] = $url[0] . "_" . $i . ".shtml";
    }
    print_r($url);
    $url[0] = $url[0] . ".shtml";
//$fuck .= 3;
//print_r($fuck);

    $output = "";

    foreach ($url as $surl) {
        $content = file_get_contents($surl);

//    print_r($content);
        $content=iconv("gb2312","UTF-8",$content);
        $js_pattern = "/<p>.*<strong>(.*?)<\/strong>.*<\/p>/";

        preg_match_all($js_pattern, $content, $matches);
        print_r($matches[1]);

        $output .= $matches[1][0] . "\n";
    }
    print_r($output);
}



function findJsAddress($url) {
    $content = file_get_contents($url);

    print_r($content);

    $js_pattern = "/http.*\.js/";

    preg_match_all($js_pattern, $content, $matches);
    print_r($matches[0]);

    foreach ($matches[0] as $js_address) {
        $js_content = file_get_contents($js_address);
//    print_r($js_content);
        preg_match_all("/.*more.*/", $js_content, $js_matches);
        print_r($js_matches[0]);

    }
}

function getInternalLink($url) {
    $content = file_get_contents($url);
    print_r($content);
    $link_pattern = "/.*/";
    $uri_pattern = "/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/";
    preg_match_all($uri_pattern, $content, $matches);
    print_r($matches);


}

function parseUri($str) {
    $uri_pattern = "/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/";
    preg_match_all($uri_pattern, $str, $matches);
    print_r($matches);
}

function download($file_url,$new_name=''){
    if(!isset($file_url)||trim($file_url)==''){
        return '500';
    }
    if(!file_exists($file_url)){ //检查文件是否存在
        return '404';
    }
    $file_name=basename($file_url);
    $file_type=explode('.',$file_url);
    $file_type=$file_type[count($file_type)-1];
    $file_name=trim($new_name=='')?$file_name:urlencode($new_name).'.'.$file_type;
    $file_type=fopen($file_url,'r'); //打开文件
    //输入文件标签
    header("Content-type: application/octet-stream");
    header("Accept-Ranges: bytes");
    header("Accept-Length: ".filesize($file_url));
    header("Content-Disposition: attachment; filename=".$file_name);
    //输出文件内容
    echo fread($file_type,filesize($file_url));
    fclose($file_type);
}

$url = "http://ugrs.zju.edu.cn/redir.php?catalog_id=1005611";
$content = file_get_contents($url);
$content = iconv("gb2312", "utf-8//IGNORE", $content);
$content = preg_replace("/[ \t]/", "", $content);
$content = preg_replace("/<tr>/", "", $content);
$content = preg_replace("/<\/tr>/", "", $content);
$content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
preg_match_all('/<divclass="zjpy_04"><ahref="(.*)"style="width:846px;display:block;height:30px;padding-left:20px;color:#306bad;font-weight:bolder;text-decoration:none;border-bottom:1px#68b5easolid;background:url\(\/template\/images\/zjpy_Bg\.jpg\)no-repeat2px10px;line-height:30px;">(.*)<\/a><\/div>\n<tablewidth="100%"border="0"cellpadding="8"cellspacing="1"bgcolor="#d2d9e3"style="margin:10px000;">\n<tdwidth="235"bgcolor="#FFFFFF"><ahref="(.*)"title=.*>(.*)<\/a>&nbsp;<\/td>\n/', $content, $index);
preg_match_all('/<\/tr>\n|<tdwidth="235"bgcolor="#FFFFFF"><ahref="(.*)"title=.*>(.*)<\/a>&nbsp;<\/td>\n/', $content, $entries);
$pointer = -1;
mkdir("/users/zhenghu/pyfa/", 0777);
$domain = "http://ugrs.zju.edu.cn";

for ($i = 0; $i < count($entries[0]); $i++) {
    if ($pointer < count($index[4]) - 1) {
        if ($entries[2][$i] === $index[4][$pointer+1]) {
            $pointer++;
            $pathname = "/users/zhenghu/pyfa/" . $index[2][$pointer];
            mkdir($pathname, 0777);
            $wget_command  = "wget -O " . $pathname . "/" . $index[2][$pointer] . ".pdf " . '"' . $domain . $index[1][$pointer] . '"';
            echo "\n";
            print_r($wget_command);
            shell_exec($wget_command);

        }
    }
    $pathname = "/users/zhenghu/pyfa/" . $index[2][$pointer];
    $wget_command  = "wget -O " . $pathname . "/" . $entries[2][$i] . ".pdf " . '"' . $domain . "/" . $entries[1][$i] . '"';
    echo "\n";
    print_r($wget_command);
    shell_exec($wget_command);
}
?>