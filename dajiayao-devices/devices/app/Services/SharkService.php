<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/5/13
 * Time: 13:59
 */
namespace Dajiayao\Services;

use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\ShakeInfo;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\WeixinPage;
use Dajiayao\Model\WxUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class SharkService extends BaseController{


    const REDIS_PREFIX = "Dajiayao:device:sharkinfo:";

    public function __construct()
    {
        $this->redis = Redis::connection();

        if( ! $this->redis){
            throw(new \Exception("redis connection error",80000));
        }

    }

    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param $ticket
     * @param int $needPoi
     * @return mixed
     * @throws \Exception
     */
    public function setInfoInDB(ShakeAroundClient $shakeAroundClient,$ticket,$needPoi=1,$appid=null,$appsecret=null)
    {
        $ret = json_decode($this->parseRetInfo($shakeAroundClient,$ticket,$needPoi,$appid,$appsecret));
        $newShark = new ShakeInfo();
        $newShark->page_id = $ret->page_id;
        $newShark->wx_page_id = $ret->wx_page_id;
        $newShark->wx_device_id = $ret->wx_device_id;
        $newShark->distance = $ret->distance;
        $newShark->uuid = $ret->uuid;
        $newShark->major = $ret->major;
        $newShark->minor = $ret->minor;
        $newShark->openid = $ret->openid;
        $newShark->poi_id = $ret->poi_id;
        $newShark->save();
        return $newShark;

    }

    /**
     * 将微信返回的ticket，取得数据，然后本地解析
     * @param ShakeAroundClient $shakeAroundClient
     * @param $ticket
     * @param int $needPoi
     * @return string
     * @throws \Exception
     */
    public function parseRetInfo(ShakeAroundClient $shakeAroundClient,$ticket,$needPoi,$appid=null,$appsecret=null)
    {
        $accessToken = $this->getWeixinToken($appid,$appsecret);

        $ret = $shakeAroundClient->getShakeInfo($ticket,$needPoi,$accessToken);

        if($ret->errcode != 0){
            throw(new \Exception("weixin error:".$ret->errmsg,90000));
        }

        $wxPageId = $ret->data->page_id;
        $page = WeixinPage::where('page_id',$wxPageId)->first();

        if( ! $page){
            throw(new \Exception(sprintf("page_id %s not found",$wxPageId),23001));
        }



        $objRet = new \stdClass();
        $objRet->page_id = $page->id;
        $objRet->wx_page_id = $wxPageId;
        $objRet->title = $page->title;
        $objRet->description = $page->description;
        $objRet->icon_url = $page->icon_url;
        $objRet->url = $page->url;
        $objRet->comment = $page->comment;
        if($needPoi == 1){
            $objRet->poi_id = $ret->data->poi_id;
        }else{
            $objRet->poi_id = 0;
        }

        $deviceUuid = $ret->data->beacon_info->uuid;
        $deviceMajor = $ret->data->beacon_info->major;
        $deviceMinor = $ret->data->beacon_info->minor;
        $device = WeixinDevice::where('uuid',$deviceUuid)->where('major',$deviceMajor)->where('minor',$deviceMinor)->first();
        if( ! $device){
            throw(new \Exception(sprintf("device Uuid: %s not found",$deviceUuid),24001));
        }
        $objRet->wx_device_id = $device->device_id;
        $objRet->device_id = $device->id;
        $objRet->major = $ret->data->beacon_info->major;
        $objRet->minor = $ret->data->beacon_info->minor;
        $objRet->uuid = $ret->data->beacon_info->uuid;
        $objRet->openid = $ret->data->openid;
        $objRet->distance = $ret->data->beacon_info->distance;

        $this->openid = $objRet->openid;

        return json_encode($objRet);

    }


    /**
     * 将微信返回的sharkinfo 存入本地redis
     * @param ShakeAroundClient $shakeAroundClient
     * @param $ticket 微信端返回的token
     * @param int $needPoi 是否返回门店的信息
     * @return mixed
     * @throws \Exception
     */
    public function setInfoInRedis(ShakeAroundClient $shakeAroundClient,$localTicket,$ticket,$needPoi=1,$appid=null,$appsecret=null)
    {

        $ret = json_decode($this->parseRetInfo($shakeAroundClient,$ticket,$needPoi,$appid,$appsecret));

        $retPage = array();
        $retPage['page_id'] = $ret->page_id;
        $retPage['title'] = $ret->title;
        $retPage['description'] = $ret->description;
        $retPage['icon_url'] = $ret->icon_url;
        $retPage['url'] = $ret->url;
        $retPage['comment'] = $ret->comment;

        $retDevice = array();
        $retDevice['device_id'] = $ret->device_id;
        $retDevice['distance'] = $ret->distance;

        $retIntoRedis = ['page'=>$retPage,'device'=>$retDevice,'openid'=>$ret->openid];

        return $this->redis->setex(self::REDIS_PREFIX.$localTicket,60*30,json_encode($retIntoRedis));

    }


    /**
     * 通过tiecket取得 sharkinfo
     * @param null $ticket
     * @return mixed
     * @throws \Exception
     */
    public function getInfoFromRedis($localTicket=null)
    {
        if( ! $localTicket){
            throw(new \Exception("ticket is required"));
        }

        $ret = $this->redis->get(self::REDIS_PREFIX.$localTicket);

        if( ! $ret){
            throw(new \Exception("shark info expired",25002));
        }
        return json_decode($ret);
    }

    /**
     * 摇一摇保存用户的信息
     * @param WeixinUserService $weixinUserService
     * @param $openid
     * @param $appid
     * @param $appsecret
     * @param $mpId
     * @param $wxMpId
     * @return mixed
     * @author zhengqian@dajiayao.cc
     */
    public function saveUserInfo(WeixinUserService $weixinUserService,$openid,$appid,$appsecret,$mpId,$wxMpId)
    {
        if($user = WxUser::where('open_id',$openid)->first()){
            return $user->id;
        }

        $wxClient = new WeixinClient();
        $ret = $weixinUserService->getUserInfo($wxClient,$openid,$appid,$appsecret);

        $unionid = isset($ret->unionid) ? $ret->unionid : 0;

        $arr = [
            'subscribe'=>$ret->subscribe,
            'open_id'=>$ret->openid,
            'nickname'=>$ret->nickname,
            'sex'=>$ret->sex,
            'language'=>$ret->language,
            'city'=>$ret->city,
            'province'=>$ret->province,
            'country'=>$ret->country,
            'headimgurl'=>$ret->headimgurl,
            'subscribe_time'=>date("Y-m-d H:i:s",$ret->subscribe_time),
            'unionid'=>$unionid,
            'mp_id'=>$mpId,
            'wx_mp_id'=>$wxMpId
        ];

        return $weixinUserService->setUserInfoInDB($arr);
    }



}