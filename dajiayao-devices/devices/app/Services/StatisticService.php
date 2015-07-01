<?php

namespace Dajiayao\Services;

use Dajiayao\Library\Weixin\DeviceIdentifier;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinPage;

class StatisticService extends BaseController
{

    protected  $sharkroundClient;

    public function __construct(ShakeAroundClient $shakeAroundClient)
    {
        $this->sharkroundClient = $shakeAroundClient;
    }
    /** 基于设备的数据统计
     * @param ShakeAroundClient $shakeAroundClient
     * @param $deviceId
     * @param $beginDate
     * @param $endDate
     * @param null $appid
     * @param null $appsecret
     * @return mixed
     * @throws \Exception
     * @author zhengqian@dajiayao.cc
     */
    public function deviceStatistic($deviceId,$beginDate,$endDate,$appid=null,$appsecret=null)
    {
        $wxDevice = WeixinDevice::find($deviceId);
        if( ! $wxDevice){
            throw new \Exception(sprintf("device Id: %s not found in db",$deviceId),24001);
        }

        $deviceIdentifier = new DeviceIdentifier($wxDevice->device_id,$wxDevice->uuid,$wxDevice->major,$wxDevice->minor);

        $token = $this->getWeixinToken($appid,$appsecret);

        $ret = $this->sharkroundClient->statisticsDevice($deviceIdentifier,intval($beginDate),intval($endDate),$token);

        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,$ret->errcode));
        }

        return $ret->data;
    }

    /** 基于页面的数据统计
     * @param ShakeAroundClient $shakeAroundClient
     * @param $pageId
     * @param $beginDate
     * @param $endDate
     * @param null $appid
     * @param null $appsecret
     * @return mixed
     * @throws \Exception
     * @author zhengqian@dajiayao.cc
     */
    public function pageStatistic($pageId,$beginDate,$endDate,$appid=null,$appsecret=null)
    {

        $wxPage = WeixinPage::find($pageId);

        if( ! $wxPage){
            throw new \Exception(sprintf("page id : %s not found in db",$pageId),23001);
        }

        $token = $this->getWeixinToken($appid,$appsecret);

        $ret = $this->sharkroundClient->statisticsPage($wxPage->page_id,intval($beginDate),intval($endDate),$token);

        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,$ret->errcode));
        }

        return $ret->data;
    }
}