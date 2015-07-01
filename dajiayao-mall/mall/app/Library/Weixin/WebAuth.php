<?php namespace Dajiayao\Library\Weixin;

use Dajiayao\Library\Help\Tool;
use \Config;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/9
 */

class WebAuth
{
    const WEB_AUTH = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

    const GET_USER_INFO_API = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s';


    /**
     * 通过code获取用户openid
     * @param $code
     * @return mixed
     * @author zhengqian@dajiayao.cc
     */
    public static  function getOpenId($code,$type){
        $appid = $appid = Config::get("weixin.$type.appid");
        $appsecret = Config::get("weixin.$type.appsecret");
        $api = sprintf(self::WEB_AUTH,$appid,$appsecret,$code);
        try{
            $ret = Tool::getCurl($api,30);
            if($ret->httpCode != 200 or $ret->error or $ret->errno){
                throw new \Exception($ret->error,$ret->errno);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            die();
        }
        $obj = json_decode($ret->content);
        if(isset($obj->errcode)){
            die(sprintf("wx getAccess error,errcode:%d ,errmsg:%s",$obj->errcode,$obj->errmsg));
        }
        return $obj->openid;
    }


    /**
     * @对外接口，获取用户信息
     * @param null
     * @return mixed
     */
    public static function getUserInfo($accessToken,$openid,$lang="zh_CN")
    {
        $api = sprintf(self::GET_USER_INFO_API,$accessToken,$openid,$lang);
        $ret = Tool::getCurl($api,30);
        if($ret->httpCode !== 200 or $ret->error or $ret->errno){
            throw new \Exception("curl get user info error");
        }
        $ret =  $ret->content;

        $userinfo = \Cache::get($openid);
        if ( ! $userinfo){
            \Cache::put($openid,$ret,60*24);
        }
        return \Cache::get($openid);
    }


}