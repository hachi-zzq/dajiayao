<?php namespace Dajiayao\Library\Help;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/15
 */

class ClientHelp
{


    /**
     * @param $url
     * @param string $type
     * @param array $postData
     * @param int $timeout
     * @return \stdClass
     */
    public static function getCurl($url,$type='get',$postData=array(),$timeout=60)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
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

}