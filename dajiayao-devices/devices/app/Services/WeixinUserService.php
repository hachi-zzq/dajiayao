<?php namespace Dajiayao\Services;


use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\WxUser;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/21
 */

class WeixinUserService extends BaseController
{

    public function getUserInfo(WeixinClient $weixinClient,$openid,$appid,$appsecret)
    {
        $accessToken = $this->getWeixinToken($appid,$appsecret);

        $ret = $weixinClient->getUserInfoByOpenId($openid,$accessToken);

        return $ret;

    }


    public function setUserInfoInDB(array $array)
    {
        $wxUser = new WxUser();
        foreach ($array as $k=>$v) {
            $wxUser->$k = $v;
        }
        $wxUser->save();

        return $wxUser->id;

    }
}