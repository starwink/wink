<?php
/**
 *全居调用函数代码集
 */

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}


/**
 * 通数组生成字符串条件值
 *
 * @param array $array 数组名
 *
 * @author 韩雷鸣
 */
 function arrayToString($array = array())
{
    $return = '';
    if(is_array($array)){
        $array = array_unique($array);
        foreach($array as $k => $v){
            if( !empty($v)){
                $return .= $v . ',';
            }
        }
    }
    if( !empty($return)){
        $return = ',' . $return;
    }
    return $return;
}
/**
 * 这个是用于echo结束返回数据的方法
 * $array是需要json化的数据(必要)
 * @author 韩雷鸣
 * 创建于2015/02/11
 */
 function echo_json_exit($array)
{

    $array = CJSON::encode($array);
    header("Content-type:application/json; charset=utf-8");
    echo $array;
    exit;
}
/**
 * 这个是用于echo结束返回数据的方法,汉字未处理
 * $array是需要json化的数据(必要)
 * @author 韩雷鸣
 * 创建于2015/02/11
 */
 function echo_json_exitc($array)
{
    $data_json = json_encode($array ,JSON_UNESCAPED_UNICODE);
    header("Content-type:application/json; charset=utf-8");
    echo $data_json;
    exit;
}
/**
 * yii对象化数组转数组
 *
 * @param array $data
 *
 * @internal param \yii对像化数组 $array $data yii对像化数组
 *
 * @return array
 * @author   韩雷鸣
 */
 function yiiObjectToArray($data = array())
{
    $ret_data = array();
    foreach($data as $k => $v){
        $ret_data[] = $v->Attributes;
    }
    return $ret_data;
}
/**
 * 二维数据排序
 *
 * @param        $arr  数据名
 * @param        $keys k名称
 * @param string $type 排序类型
 *
 * @return array
 * @author 韩雷鸣
 */
 function array_sort($arr ,$keys ,$type = 'asc')
{
    $keysvalue = $new_array = array();
    foreach($arr as $k => $v){
        $keysvalue[$k] = $v[$keys];
    }
    if($type == 'asc'){
        asort($keysvalue); //排序
    }else{
        arsort($keysvalue); //倒序
    }
    reset($keysvalue);
    foreach($keysvalue as $k => $v){
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}
/**
 * 多维数组去掉重复值
 */
function array_unique_fb($array2D)
{
    foreach($array2D as $v){
        $v = join("," ,$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        $temp[] = $v;
    }
    $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
    foreach($temp as $k => $v){
        $temp[$k] = explode("," ,$v); //再将拆开的数组重新组装
    }
    return $temp;
}

//读取
function R_FileData($Dname){
    $fp = fopen($Dname,'r');
    $contents = fread ($fp, filesize ($Dname));
    fclose($fp);

    return $contents;

}
//保存
function B_FileData($Dname,$Data){
    $fp = fopen($Dname,'wb+');
    fwrite($fp,$Data);
    fclose($fp);
}
//生成数组文件内容
function arrayeval($array, $level = 0) {
    $space = '';
    for($i = 0; $i <= $level; $i++) {
        $space .= "\t";
    }
    $evaluate = "Array ( \n";
    //$comma = $space;
    $comma = '';
    foreach($array as $key => $val) {
        $key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
        $val = !is_array($val) && (!preg_match("/^\-?\d+$/", $val) || strlen($val) > 12 || substr($val, 0, 1)=='0') ? '\''.addcslashes($val, '\'\\').'\'' : $val;
        if(is_array($val)) {
            $evaluate .=  "\t\n"."$comma$key => ".arrayeval($val, $level + 1);
        } else {
            $evaluate .= "$comma$key => $val";
        }
        $comma = ",\n";
    }
    $evaluate .= "\n)\n";
    return $evaluate;
}
//写入文件
function swritefile($filename, $writetext, $openmod='w') {
    if($fp = fopen($filename, $openmod)) {
        flock($fp, 2);
        fwrite($fp, $writetext);
        fclose($fp);
        chmod($filename, 0755); //权限修改
        return true;
    } else {
        runlog('error', "File: $filename write error.");
        return false;
    }
}
/**
 * 数据存储到本地文件
 * @param string    $file_name  存储文件名
 * @param array     $array_data 存储的数组
 * @param string    $file_url  文件存储路径
 * $rs = JavaData::ReturnJavaData(); 调用方法  JavaData为文件名
 */
function ArrayToFile($file_name,$array_data,$file_url=''){
    if(empty($file_name) || !is_array($array_data) ) return false;
    if(empty($file_url)) $file_url=Yii::app()->baseUrl.'protected/models/';

    $cachefile = $file_url.$file_name.'.php';
    $cachetext = "<?php //更新于 ".date('Y-m-d H:i:s')." \r\n".
        'class '.$file_name." \r\n".
        '{'." \r\n".
        '    public static function getData()'. " \r\n".
        '    {  '." \r\n".
        '       return '.arrayeval($array_data).
        ";\r\n".
        '    }'."\r\n".
        '}';
    if(!swritefile($cachefile, $cachetext)) {
        return false;
    }else{
        return true;
    }
}

/**
 * 去掉html标签，回车
 * @param $str
 *
 * @return string
 */
function DeleteHtml($str)
{
    $str = trim($str);
    $str = strip_tags($str,""); // 函数剥去 HTML、XML 以及 PHP 的标签。

    $str = str_replace("\t","",$str); //函数替换，
    $str = str_replace("\r\n","",$str);
    $str = str_replace("\r","",$str);
    $str = str_replace("\n","",$str);
    $str = str_replace(" ","",$str);
    $str = str_replace("'","&acute;",$str);  //单引号SQL语句漏洞
    return trim($str);
}

/**
 * 去掉格式，回车
 * @param $str
 *
 * @return string
 */
function ClearFormat($str)
{
    $str = str_replace("\t","",$str); //函数替换，
    $str = str_replace("\r\n","",$str);
    $str = str_replace("\r","",$str);
    $str = str_replace("\n","",$str);
    $str = str_replace(" ","",$str);
    return trim($str);
}


/**
 * 防注入过滤
 * @param $val 参数
 *
 * @return string
 */
function Iclev($val){
    return Addslashes($val);
}