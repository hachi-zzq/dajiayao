<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/6/5
 * Time: 16:58
 */

namespace Dajiayao\Http\Controllers;


use Dajiayao\Library\Mq\MQ;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\WxUser;
use Dajiayao\Services\WxUserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

abstract class WeixinController extends Controller
{

    const WX_MSG_TYPE_TEXT = 'text';//用户发送消息推送
    const WX_MSG_TYPE_EVENT = 'event';//事件推送
    const WX_MSG_EVENT_SUBSCRIBE = 'subscribe';//订阅事件
    const WX_MSG_EVENT_CLICK = 'CLICK';//菜单点击事件

    const WX_MENU_KEY_SEARCH_LESSON = 'SEARCH_LESSON';//搜索菜单的KEY
    const WX_MENU_KEY_ABOUT_XY = 'ABOUT_XY';//搜索菜单的KEY

    const WX_CLICK_EVENT_NO_RESPOND_TIMEOUT = 10;//针对微信菜单点击搜索事件无反应的超时时间

    const WX_QUIT_KEY = "q";//菜单事件退出开关

    const WX_ACCOUNT_BUYER = "buyer";
    const WX_ACCOUNT_SELLER = "seller";


    protected $mq;
    protected $weixinClient;


    public function __construct(WxUserService $wxUserService)
    {
        $this->mq = new MQ();
        $this->weixinClient = new WeixinClient();
        $this->wxUserService = $wxUserService;
    }


    /**
     * @用于服务器接入的测试
     * check token
     */
    abstract function checkSignature();

    /**
     * 处理微信调用的请求
     */
    public function index()
    {
        $message = file_get_contents("php://input");
        Log::info($message);
        $message = simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($message) {
            $msgType = $message->MsgType;
            $fromUser = $message->FromUserName;
            $content = $message->Content;
            if ($msgType == self::WX_MSG_TYPE_TEXT) {
                //处理用户文字
                return $this->handleUserMassage($message);
            }
            if ($msgType == self::WX_MSG_TYPE_EVENT) {
                if ($message->Event == self::WX_MSG_EVENT_SUBSCRIBE) {
                    //处理用户订阅事件
                    return $this->handleSubscribeEvent($message);
                }
                if ($message->Event == self::WX_MSG_EVENT_CLICK) {
                    if ($message->EventKey == self::WX_MENU_KEY_ABOUT_XY) {
                        //处理用户点击关于菜单事件
                        return $this->handleClickAboutMenu($message);
                    }
                }
            }
            \Log::info("msgType = $msgType");
        }
        return "";
    }

    /**
     * 处理用户文字消息
     * @param $message
     * @return mixed
     * Author: Haiming.Wang<haiming.wang@enstar.com>
     */
    protected abstract function handleUserMassage($message);

    /**
     * 关于菜单
     * @param $message
     * @return mixed
     * Author: Haiming.Wang<haiming.wang@enstar.com>
     */
    protected abstract function handleClickAboutMenu($message);

    /**
     * @param $message
     * 处理关注事件,写入用户信息，回复问候语
     * @return string
     */
    protected abstract function handleSubscribeEvent($message);

    /**
     * 获得并保存关注的用用户信息
     * @param $openId
     * @param $accessToken
     * @return WxUser|null
     */
    protected function getSubscribeUser($openId, $accessToken, $role)
    {
        $wxUserInfo = $this->weixinClient->getUserInfoByOpenId($openId, $accessToken);
        if (!$wxUserInfo) {
            return null;
        }
        $user = $this->wxUserService->getWxUserByOpenId($openId);
        if (!$user) {
            $user = new WxUser();
            $user->openid = $openId;
        }
        $user->subscribe = $wxUserInfo->subscribe;
        $user->openid = $openId;
        $user->nickname = $wxUserInfo->nickname;
        $user->sex = $wxUserInfo->sex;
        $user->language = $wxUserInfo->language;
        $user->city = $wxUserInfo->city;
        $user->province = $wxUserInfo->province;
        $user->country = $wxUserInfo->country;
        $user->headimgurl = $wxUserInfo->headimgurl;
        $user->unionid = null;
        $user->role = $role;
        $user->save();
        return $user;
    }

    /**
     * 回复普通信息
     * @param $toUserName
     * @param $content
     * @param $fromUserName
     * @return mixed
     */
    protected function respondTextMsg($toUserName, $content, $fromUserName)
    {
        $rsMsg = new \stdClass();
        $rsMsg->FromUserName = $fromUserName;
        $rsMsg->ToUserName = $toUserName;
        $rsMsg->Content = $content;
        return view('wx.respond_text')->with('message', $rsMsg);
    }


}