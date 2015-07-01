<?php
/**
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/5/6
 * Time: 9:03
 */

namespace Dajiayao\Library\Weixin;

use Illuminate\Support\Facades\Redis;
use \Log;

class WeixinClient
{

    const GET_USER_INFO = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s";
    const GET_TOKEN = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";

    /**
     * 根据openid获得微信用户信息
     * @param $openId
     * @param $access_token
     * @param string $lang
     * @return null
     */
    public function getUserInfoByOpenId($openId, $access_token, $lang = 'zh_CN')
    {
        $url = sprintf(self::GET_USER_INFO,$access_token,$openId,$lang);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if (array_key_exists('errcode', $rtJson)) {
            Log::info("Get user info error.[openid:$openId,errormsg:$rtJson->errmsg]");
            return null;
        }
        return $rtJson;
    }

    /**
     * @param $data_string
     * @param $url
     * @return mixed
     */
    protected function doPost($url,$data_string)
    {
        $headers = array('Content-Type' => 'application/json');
        $response = \Requests::post($url, $headers, $data_string);
        return json_decode($response->body);
    }

    /**
     * 获得access_token
     * @return null
     */
    public function applyAccessToken($appid, $secret)
    {
//        $redis = Redis::connection();
//        if( ! $redis){
//            throw new \Exception("redis connect error");
//        }
//        $accessToken = $redis->get('dajiayao.device.'.$appid);
//        if( ! $accessToken){
//            $url = sprintf(self::GET_TOKEN,$appid,$secret);
//            $response = \Requests::get($url);
//            $rtJson = $response->body;
//            $rtJson = json_decode($rtJson);
//            if (array_key_exists('access_token', $rtJson)) {
//                $redis->setex('dajiayao.device.'.$appid,7000,$rtJson->access_token);
//            }else{
//                throw new \Exception("weixin get access_token error");
//            }
//        }
//
//        return $redis->get('dajiayao.device.'.$appid);
        //TODO 后期需要从缓存或者从access_token中央服务器中获取
        $url = sprintf(self::GET_TOKEN,$appid,$secret);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if (array_key_exists('access_token', $rtJson)) {
            return $rtJson->access_token;
        }else{
            throw new \Exception("weixin get access_token error");
        }
    }


    /**
     * 下载远程文件到本地
     * @param $url
     * @param $fileName
     * @return mixed
     * @throws \Exception
     */
    public function downloadFile($url,$fileName)
    {
        file_put_contents($fileName,file_get_contents($url));
        if( ! file_exists($fileName)){
            throw new \Exception("file download fail");
        }

        return $fileName;

    }

}