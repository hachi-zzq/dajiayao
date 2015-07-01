<?php namespace Dajiayao\Http\Controllers\WeixinAuth;

use Dajiayao\Model\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Dajiayao\Library\Weixin\WebAuth;
use Dajiayao\Library\Mq\MQ;
use Dajiayao\Model\WxUser;
use Dajiayao\Http\Controllers\Controller;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/4
 */
abstract class WeixinAuthController extends Controller
{

    protected $openid;
    protected $request;
    protected $appid;
    protected $type;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function auth()
    {
        $authInput = $this->request->only('code','state');
        if (isset($authInput['code']) && $authInput['state'] == 'dajiayao123456') {
            return $this->getOpenidOnLine($authInput['code']);
        }else{
            throw new \Exception("Permission denied");
        }
    }

    /**
     * @请求微信api，获取openid
     * @param $code
     * @author: zhengqian.zhu@enstar.com
     */
    public function getOpenidOnLine($code)
    {
        $this->openid = WebAuth::getOpenId($code,$this->type);
        if( ! $this->openid){
            throw new \Exception("weixin getOpenId error");
        }
        Session::put('openid', $this->openid);
        \Log::info(sprintf("openid : %s",$this->openid));
        return $this->saveUserInfo();
    }
    /**
     * @获取用户信息
     * @author zhengqian.zhu
     */
    public function saveUserInfo()
    {
        $mq = new MQ();
        $accessToken = $mq->getWeixinAccessTokenByName($this->type);

        if( ! $accessToken){
            throw new \Exception("get buyer access token error");
        }
        $userJson = WebAuth::getUserInfo($accessToken,$this->openid);
        $wxUserId = WxUser::saveWxUser($userJson);
        //保存卖家
        $buyer = new Buyer();
        $buyer->wx_user_id = $wxUserId;
        $buyer->save();
        Session::put('buyer_id',Buyer::where('wx_user_id',$wxUserId)->first()->id);
        \Log::info(sprintf("buyer_id : %s",$buyer));
        return $this->redirectRequestUrl();
    }
    /**
     * @跳转原始的requestUrl
     * @return mixed
     * @author: zhengqian.zhu@enstar.com
     */
    public function redirectRequestUrl()
    {
        $requestUrl = Session::get("request_url");
        if( ! $requestUrl){
//            throw new \Exception("redirect url is null");
            echo "打开的链接非法，请从小店入口打开！";
            exit;
        }

        return redirect(Session::get("request_url"));
    }
}