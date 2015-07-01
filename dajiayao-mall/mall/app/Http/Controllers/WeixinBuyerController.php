<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/6/5
 * Time: 16:58
 */

namespace Dajiayao\Http\Controllers;

use Dajiayao\Model\WxUser;
use Dajiayao\Services\WxUserService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class WeixinBuyerController extends WeixinController
{

    public function __construct(WxUserService $wxUserService)
    {
        parent::__construct($wxUserService);
    }


    /**
     * @用于服务器接入的测试
     * check token
     */
    public function checkSignature()
    {
        echo Input::get("echostr");
    }


    /**
     * 关于菜单
     * @param $message
     * @return mixed
     * Author: Haiming.Wang<haiming.wang@enstar.com>
     */
    protected function handleClickAboutMenu($message)
    {
        $fromUser = $message->FromUserName;
        $toUser = $message->ToUserName;
        return $this->respondTextMsg($fromUser, "About buyer weixin", $toUser);
    }


    /**
     * 处理关注事件,写入用户信息，回复问候语
     * @return string
     */
    protected function handleSubscribeEvent($message)
    {
        $fromUserName = $message->FromUserName;
        $wxUser = $this->getSubscribeUser($fromUserName, $this->mq->getWeixinAccessTokenByName(self::WX_ACCOUNT_BUYER), WxUser::ROLE_BUYER);
        return $this->respondTextMsg($fromUserName, Config::get('weixin.greetings'), $message->ToUserName);
    }

    /**
     * 处理用户文字消息
     * @param $message
     * @return mixed
     * Author: Haiming.Wang<haiming.wang@enstar.com>
     */
    protected function handleUserMassage($message)
    {
        $fromUserName = $message->FromUserName;
        $toUserName = $message->ToUserName;
        $time = time();
//        $res = "<xml>"
//            ."<ToUserName><![CDATA[$fromUserName]]></ToUserName>"
//            ."<FromUserName><![CDATA[$toUserName]]></FromUserName>"
//            ."<CreateTime>$time</CreateTime>"
//            ."<MsgType><![CDATA[transfer_customer_service]]></MsgType>"
//            ."</xml>";
//        header("Content-type: text/xml");
//        echo $res;
        return view('wx.dkf')
            ->with('fromUserName', $fromUserName)
            ->with('toUserName', $toUserName)
            ->with('time', $time);
    }
}