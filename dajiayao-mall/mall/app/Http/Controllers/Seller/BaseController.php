<?php namespace Dajiayao\Http\Controllers\Seller;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Library\Help\Yunpian;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\SmsCode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Input;
use Validator;
use Dajiayao\Library\Help\Tool;
use Dajiayao\Library\Mq\MQ;

class BaseController extends Controller {

    public function __construct()
    {
        /**
         * session 已经在 WeixinSellerAuthController 微信授权，取到用户身份的时候 生成
         * @author zhengqian.zhu
         */
        $this->sellerId = Session::get('seller_id');
    }

    /**
     * 发送短信验证码
     * @author Hanxiang
     */
    public function sendSmsCode() {

        $input = Input::all();
        $validator = Validator::make($input, [
            'mobile' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendResponse(20101, $validator->messages()->first());
        }
        if (!preg_match('/^[1][34578]\d{9}$/', $input['mobile'])) {
            return $this->sendResponse(20102, 'mobile invalid');
        }

        $code = mt_rand(1000, 9999);
        $check = SmsCode::checkMobile($input['mobile'], $code);
        if (!$check) {
            return $this->sendResponse(20103, 'please retry after 30s');
        }

        Yunpian::sendSmsCode($input['mobile'], $code);
        return $this->sendResponse(10000, 'success');
    }

    public function sendResponse($code, $msg, $data = '') {
        if (empty($data)) {
            $data = new \stdClass();
        }
        return response()->json(['msgcode' => $code, 'msg' => $msg, 'data' => $data]);
    }

    public function getJsapiConfig()
    {
        $appid = Config::get('weixin.seller.appid');
        $appsecret = Config::get('weixin.seller.appsecret');
        $mq = new MQ();
        $jsapiTicket = $mq->getWeixinJsapiTicket($appid, $appsecret);
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
        $config->appid  = $appid;

        return $config;
    }
}
