<?php namespace Dajiayao\Library\Help;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/9
 */

class Tool
{
    public static function getCurl($url,$timeout=60,$type='get',$postData=array())
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //定义超时60秒钟
        if($type=='post'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$postData);
        }
        $content = curl_exec($curl);
        $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        $curlErrno = curl_errno($curl);
        curl_close($curl);
        $retObj = new \stdClass();
        $retObj->httpCode = $httpCode;
        $retObj->content = $content;
        $retObj->error = $curlError;
        $retObj->errno = $curlErrno;
        return $retObj;
    }


    /**
     * 获得随机字符串
     * @param $length
     * @return null|string
     */
    public static function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
}