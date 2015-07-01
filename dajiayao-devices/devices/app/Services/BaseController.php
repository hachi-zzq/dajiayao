<?php namespace Dajiayao\Services;

use Dajiayao\Library\Weixin\WeixinClient;
use Illuminate\Support\Facades\Session;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/14
 */

class BaseController
{


    /**
     * 生产微信的token
     * @return null
     * @throws \Exception
     */
    public function getWeixinToken($appid=null,$appsecret=null)
    {
        $wxClient = new WeixinClient();
        $appid = is_null($appid) ? Session::get('wx_appid') : $appid;
        $appsecret = is_null($appsecret) ? Session::get('wx_appsecret') : $appsecret;
        $accessToken = $wxClient->applyAccessToken($appid,$appsecret);

        if($accessToken == NULL){
            throw(new \Exception("weixin get token error",9000));
        }
        return $accessToken;
    }
}