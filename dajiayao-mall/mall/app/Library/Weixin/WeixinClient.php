<?php

/*
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/6/5
 * Time: 14:50
 */

namespace Dajiayao\Library\Weixin;

use Illuminate\Support\Facades\Log;

/**
 *
 * Class WeixinClient
 * @package Dajiayao\Library\Weixin
 */
class WeixinClient
{

    const API_CREATE_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s';
    const API_SET_INDUSTRY = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=%s';
    const API_GET_ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
    const API_GET_TICKET = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi';
    const API_GET_USER_INFO = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s';


    const API_SEND_TEMPLATE_MESSAGE = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s';
    const API_GET_MEDIA = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s';


    const ACCESS_TOKEN_EXPIRES_IN = 7200;

    /**
     * 根据openid获得微信用户信息
     * @param $openId
     * @param $access_token
     * @param string $lang
     * @return null
     */
    public function getUserInfoByOpenId($openId, $access_token, $lang = 'zh_CN')
    {
        $url = sprintf(self::API_GET_USER_INFO, $access_token, $openId, $lang);
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
    protected function doPost($url, $data_string)
    {
        $headers = array('Content-Type' => 'application/json');
        $response = \Requests::post($url, $headers, $data_string);
        return json_decode($response->body);
    }

    /**
     * 申请access_token
     * @param $appid
     * @param $secret
     * @return mixed
     * @throws \Exception
     */
    public function applyAccessToken($appid, $secret)
    {
        $url = sprintf(self::API_GET_ACCESS_TOKEN, $appid, $secret);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if (array_key_exists('access_token', $rtJson)) {
            return $rtJson->access_token;
        } else {
            throw new \Exception("weixin get access_token error");
        }
    }


    /**
     * 设置微信菜单
     * @param $data_string
     * @param $access_token
     * @return string
     */
    public function setMenu($data_string, $access_token)
    {
        $url = sprintf(self::API_CREATE_MENU, $access_token);
        return $this->doPost($url, $data_string);
    }

    /**
     * 设置行业
     * @param $data_string
     * @param $access_token
     * @return string
     */
    public function setIndustry($data_string, $access_token)
    {
        $url = sprintf(self::API_SET_INDUSTRY, $access_token);
        return $this->doPost($url, $data_string);
    }

    /**
     * 发送模板信息
     * @param $data_string
     * @param $access_token
     * @return string
     */
    public function sendTemplateMessage($data_string, $access_token)
    {
        $url = sprintf(self::API_SEND_TEMPLATE_MESSAGE, $access_token);
        return $this->doPost($url, $data_string);
    }


    /**
     * 获得access_token
     * @return null
     */
    public function applyJsapiTicket($access_token)
    {
        $url = sprintf(self::API_GET_TICKET, $access_token);
        $rtJson = \Requests::get($url);
        $rtBody = json_decode($rtJson->body);
        if ($rtBody->errcode !== 0) {
            throw new \Exception("get JsapiTicket error");
        }

        if (array_key_exists('ticket', $rtBody)) {
            return $rtBody->ticket;
        }
        return null;
    }

    /**
     * @param $jsapiTicket
     * @param $url
     * @param $noncestr
     * @param $timestamp
     * @return string
     */
    public function getSignature($jsapiTicket, $url, $noncestr, $timestamp)
    {
        $str = "jsapi_ticket=$jsapiTicket&noncestr=$noncestr&timestamp=$timestamp&url=$url";
        return sha1($str);
    }


}