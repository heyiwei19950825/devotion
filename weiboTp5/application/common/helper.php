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
     * 网络请求
     * @param string $url 请求地址
     * @param string $param 请求参数  xxx=xxx&xxx=xxx
     * @param string $request_method 请求方法
     * @return mixed
     */
    static function http_curl($url, $param='', $request_method='GET'){
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , false);

        if ($request_method == 'POST'){
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $param);
        }else if ($request_method == 'GET'){
            if(is_array($param)) {
                $str_p = '';
                foreach ($param as $k => $v) {
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
        $err_code = curl_errno($ch);

        curl_close($ch);
        return $data;
    }
}