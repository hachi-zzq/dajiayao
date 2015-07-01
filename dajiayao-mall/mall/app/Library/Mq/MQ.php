<?php namespace Dajiayao\Library\Mq;

use Dajiayao\Library\Device\DeviceClient;
use Dajiayao\Library\Weixin\WeixinClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

/**
 * 队列处理类
 * Class MQ
 * @author Haiming<haiming@dajiayao.cc>
 */
class MQ
{
    private $redis = '';
    private $wxClient;

    const REDIS_KEY_WX_ACCESS_TOKEN = "yayao:mall:access_token";
    const REDIS_KEY_WX_JSAPI_TICKET = "yayao:mall:jsapi_ticket";

    const REDIS_KEY_ORDER_COUNT = "yayao:mall:order:count";
    const REDIS_KEY_PAYMENT_COUNT = "yayao:mall:payment:count";

    const REDIS_KEY_DEVICE_ACCESS_TOKEN = "yayao:device:access_token";


    public function __construct()
    {
        $this->redis = Redis::connection();
        $this->wxClient = new WeixinClient();
        $this->deviceClient = new DeviceClient();
    }

    /**
     * 获得redis中微信的access_token
     * @param $appid
     * @param $appsecret
     * @return mixed
     * @throws \Exception
     */
    public function getWeixinAccessToken($appid, $appsecret)
    {
        //TODO 后续需要做缓存
//        $key = self::REDIS_KEY_WX_ACCESS_TOKEN . ':' . $appid;
//        $accessToken = $this->redis->get($key);
//        if (!$accessToken) {
//            $accessToken = $this->wxClient->applyAccessToken($appid, $appsecret);
//            $this->redis->setex($key, WeixinClient::ACCESS_TOKEN_EXPIRES_IN - 200, $accessToken);
//        }
//        return $accessToken;

        $accessToken = $this->wxClient->applyAccessToken($appid, $appsecret);
        return $accessToken;
    }


    /**
     * 根据微信公众号的类型来获取微信access_token
     * @param $name
     * @return mixed
     */
    public function getWeixinAccessTokenByName($name)
    {
        return $this->getWeixinAccessToken(Config::get("weixin.$name.appid"), Config::get("weixin.$name.appsecret"));
    }

    /**
     * 获得redis中微信的jsapi_ticket
     * @param $appid
     * @param $appsecret
     * @return null
     * @throws \Exception
     */
    public function getWeixinJsapiTicket($appid, $appsecret)
    {
        $key = self::REDIS_KEY_WX_JSAPI_TICKET . ":" . $appid;
        $jsapiTicket = $this->redis->get($key);
        if (!$jsapiTicket) {
            $accessToken = $this->getWeixinAccessToken($appid,$appsecret);
            $jsapiTicket = $this->wxClient->applyJsapiTicket($accessToken);
            $this->redis->setex($key, WeixinClient::ACCESS_TOKEN_EXPIRES_IN - 200, $jsapiTicket);
        }
        return $jsapiTicket;
    }

    /**
     * 获得redis中微信的jsapi_ticket
     * @param $name
     * @return mixed
     */
    public function getWeixinJsapiTicketByName($name)
    {
        return $this->getWeixinJsapiTicket(Config::get("weixin.$name.appid"), Config::get("weixin.$name.appsecret"));
    }


    /**
     * 获得redis中设备中心的的access_token
     * @return mixed
     */
    public function getDeviceAccessToken()
    {
        $key = self::REDIS_KEY_DEVICE_ACCESS_TOKEN;
        $accessToken = $this->redis->get($key);
        if (!$accessToken) {
            $accessToken = $this->deviceClient->applyAccessToken(Config::get("app.device.appid"), Config::get("app.device.appsecret"));
            $this->redis->setex(self::REDIS_KEY_DEVICE_ACCESS_TOKEN, DeviceClient::ACCESS_TOKEN_EXPIRES_IN - 200, $accessToken);
        }
        return $accessToken;
    }
}
