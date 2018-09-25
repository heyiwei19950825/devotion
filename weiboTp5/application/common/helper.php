<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/4
 * Time: 16:52
 */

namespace app\common;


class helper
{
    /**
     * 数据排序、删除杂项及空值
     */
    static function para_filter_sort($para) {
        $para_filter = array();
        ksort($para);

        foreach ($para as $key=>$val) {
            //将key全部小写
            $key = strtolower($key);
            if ($key === "sign" || $key === "action" || $key === "_request" || $val === "" || is_null($val)) {
                continue;
            } else {
                if (is_bool($val)) {
                    $para_filter[$key] = (int)$val;
                } else {
                    $para_filter[$key] = is_array($val) ? para_filter_sort($val) : $val;
                }
            }
        }

        return $para_filter;
    }

    /**
     * 生成拼接串
     * @param string $para
     * @return string
     */
    static function create_link_string($para){
        $arg = '';
        foreach($para as $key => $val){
            if(is_array($val)){
                $arg .= $key . '=(' . (is_array($val) ? create_link_string($val) : $val) . ')&';
            }else{
                $arg .= $key . '=' . $val . '&';
            }
        }

        if(count($arg) > 0){
            //去掉最后一个&字符
            $arg = substr($arg, 0, count($arg) - 2);
        }

        return $arg;
    }

    /**
     * 生成校验字符串
     * @param string $data
     * @return string
     */
    static function create_sign($data){
        if($data){
            // 按照key对数组进行排
            $data = para_filter_sort($data);
            // 生成拼接串
            $prestr = create_link_string($data);

            //如果存在转义字符，那么去掉转义
            if(get_magic_quotes_gpc()){
                $arg = stripslashes($arg);
            }

            //echo '$prestr = '.$prestr;
            if(empty($prestr)){
                return '';
            }else{
                $prestr .= C('API_KEY');
                return md5($prestr);
            }
        }else{
            return '';
        }
    }

    /**
     * 获取来源id
     */
    static function get_source_id($os){
        $source_id = 2;//默认安卓

        if($os){
            if(strtolower($os) == 'android'){
                $source_id = 2;
            }
            if(strtolower($os) == 'ios'){
                $source_id = 3;
            }
            if(strtolower($os) == 'weixin'){
                $source_id = 4;
            }
        }

        return $source_id;
    }

    /**
     * url跳转（原生态）
     */
    static function jump($url){
        header('Location: ' . $url);
        exit;
    }

    /**
     * 检测手机号
     */
    static function is_mobile($mobile){
        return true;
        if (preg_match("/^1[0-9]{10}$/", $mobile)) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 网络请求
     * @param string $url 请求地址
     * @param string $param 请求参数  xxx=xxx&xxx=xxx
     * @param string $request_method 请求方法
     * @return string|boolean
     */
    static function http_curl($url, $param='', $request_method='GET'){
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , false);

        if($request_method == 'POST'){
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $param);
        }else if($request_method == 'GET'){
            if(is_array($param)){
                $str_p = '';
                foreach($param as $k => $v){
                    if(empty($str_p)){
                        $str_p .= "?{$k}={$v}";
                    }else{
                        $str_p .= "&{$k}={$v}";
                    }
                }
                curl_setopt($ch, CURLOPT_URL, $url.$str_p);//抓取指定网页
            }
        }else{
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        }
        $data = curl_exec($ch);//运行curl

        if(curl_errno($ch)){
            return false;
        }

        curl_close($ch);
        return $data;
    }

    /**
     * 获取随机数
     * @param 长度 int $len
     * @param 类型 int $mode
     * @return string
     * @author liukw
     */
    static function randcode($len, $mode=2){
        $rcode = '';

        switch($mode){
            case 1: //去除0、o、O、l等易混淆字符
                $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghijkmnpqrstuvwxyz';
                break;
            case 2: //纯数字
                $chars = '0123456789';
                break;
            case 3: //全数字+大小写字母
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
                break;
            case 4: //全数字+大小写字母+一些特殊字符
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz~!@#$%^&*()';
                break;
        }

        $count = strlen($chars) - 1;
        mt_srand((double)microtime() * 1000000);
        for($i = 0; $i < $len; $i++){
            $rcode .= $chars[mt_rand(0, $count)];
        }

        return $rcode;
    }

    /**
     * 传递数据以易于阅读的样式格式化后输出
     * @param array $data
     */
    static function P($data){
        // 定义样式
        $str = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data = $data ? 'true' : 'false';
        } elseif (is_null($data)) {
            $show_data = 'null';
        } else {
            $show_data = print_r($data, true);
        }
        $str .= $show_data;
        $str .= '</pre>';
        echo $str;
    }

    /**
     * 比较是否相等
     * @param str|array $arr1
     * @param str|array $arr2
     * @param boolean $is_upper 是否大写
     * @param boolean $is_trim  是否去空格
     * @return boolean
     */
    static function compare($arr1, $arr2, $is_trim=false, $is_upper=false) {
        if (empty($arr1) || empty($arr2)) {
            return false;
        }
        if (is_string($arr1) && is_string($arr2)){
            if ($is_upper) {
                $arr1 = strtoupper($arr1);
                $arr2 = strtoupper($arr2);
            }
            if ($is_trim) {
                $find = array(" ","\t","\n","\r");
                $replace = array("","","","");
                $arr1 = str_replace($find,$replace,$arr1);
                $arr2 = str_replace($find,$replace,$arr2);
            }

            if ($arr1 === $arr2) {
                return true;
            }
        }
        return false;
    }

    /**
     * 数组去重
    **/
    static function arrayUnique($array){
        $out = [];
        
        foreach ($array as $key => $value) {
            if(!in_array($value,$out)){
                $out[$key] = $value; 
            }
        }

        return $out;
    }

    /**
     * 上传图片到百度识图接口，获取图片外链
     *
     * @param     $file 图片文件
     * @return    图片链接(上传成功)    NULL(上传失败)
     * @copyright (c) mengkun(https://mkblog.cn/1619/)
     */
    function uploadToBaidu($file) {
        // API 接口地址
        $url = 'http://image.baidu.com/pcdutu/a_upload?fr=html5&target=pcSearchImage&needJson=true';

        // 文件不存在
        if(!file_exists($file)) return '';

        // POST 文件
        if (class_exists('CURLFile')) {     // php 5.5
            $post['file'] = new CURLFile(realpath($file));
        } else {
            $post['file'] = '@'.realpath($file);
        }

        // CURL 模拟提交
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL , $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $output = curl_exec($ch);
        curl_close($ch);

        // 返回结果为空（上传失败）
        if($output == '') return '';

        // 解析数据
        $output = json_decode($output, true);
        if(isset($output['url']) && $output['url'] != '') {
            return $output['url'];
        }
        return '';
    }
}