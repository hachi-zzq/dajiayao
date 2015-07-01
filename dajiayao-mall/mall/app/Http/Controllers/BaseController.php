<?php namespace Dajiayao\Http\Controllers;
use Dajiayao\Library\Help\Tool;
use Dajiayao\Library\Mq\MQ;
use Dajiayao\Library\Weixin\WeixinClient;


/**
 * 前台基类
 * Class BaseController
 * @package Dajiayao\Http\Controllers
 */

class BaseController extends Controller
{

    /**
     * 获得微信jsapi的配置信息
     * @return stdClass
     */
    public function getJsapiConfig()
    {
        $mq = new MQ();
        $jsapiTicket = $mq->getWeixinJsapiTicketByName('buyer');
        $url = \Request::fullUrl();
        $noncestr = Tool::getRandChar(16);
        $timestamp = time();
        $weixinClient = new WeixinClient();
        $signature = $weixinClient->getSignature($jsapiTicket, $url, $noncestr, $timestamp);
        $config = new \stdClass();
        $config->jsapiTicket = $jsapiTicket;
        $config->url = $url;
        $config->noncestr = $noncestr;
        $config->timestamp = $timestamp;
        $config->signature = $signature;
        $config->appid  = \Config::get('weixin.buyer.appid');

        return $config;
    }
}