<?php namespace Dajiayao\Services;

use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\WeixinPage;
use Illuminate\Support\Facades\Log;
use J20\Uuid\Uuid;

/**
 * Class WeixinService
 * @package Dajiayao\Services
 * @author zhengqian@dajiayao.cc
 */


class WeixinService extends BaseController
{

    private $sharkClient;

    public function __construct(ShakeAroundClient $shakeAroundClient)
    {
        $this->sharkClient = $shakeAroundClient;
    }


    /**同步设备
     * @param $appid
     * @param $appsecret
     * @param int $bid
     * @param int $count
     * @return mixed
     * @throws \Exception
     * @author zhengqian@dajiayao.cc
     */
    private function syncDevice($appid,$appsecret,$bid=0,$count=10)
    {

        $token = $this->getWeixinToken($appid,$appsecret);
        $ret = $this->sharkClient->searchDeviceByRange($bid,$count,$token);

        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,90000));
        }

        return $ret->data;
    }

    /**
     * 同步页面
     * @param $appid
     * @param $appsecret
     * @param int $bid
     * @param int $count
     * @return mixed
     * @throws \Exception
     * @author zhengqian@dajiayao.cc
     */
    private function syncPage($appid,$appsecret,$bid=0,$count=10)
    {
        $token = $this->getWeixinToken($appid,$appsecret);
        $ret = $this->sharkClient->searchPageByRange($bid,$count,$token);

        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,90000));
        }

        return $ret->data;
    }

    /**
     *
     * @author zhengqian@dajiayao.cc
     */
    public function sync($appid,$appsecret)
    {
        $mp_id = WeixinMp::where('appid',$appid)->where('appsecret',$appsecret)->first()->id;
        $bid = 0;
        $countPage = 0;
        //定义缓存数组
        $cacheArrayPage = [];

        while(true){
            $ret = $this->syncPage($appid,$appsecret,$bid,20);
            $pages = $ret->pages;
            $bid = $bid + count($pages);
            if( ! $pages){
                break;
            }
            //开始同步页面
            foreach($pages as $page){
                $objPage = WeixinPage::where('page_id',$page->page_id)->first();
                if( ! $objPage){
                    $objPage = new WeixinPage();
                    $objPage->guid = Uuid::v4(false);
                    $objPage->title = $page->title;
                    $objPage->description = $page->description;
                    $objPage->icon_url = $page->icon_url;
                    $objPage->url = $page->page_url;
                    $objPage->comment = $page->comment;
                    $objPage->page_id = $page->page_id;
                    $objPage->wx_mp_id = $mp_id;
                    $objPage->save();
                    $countPage++;
                }

                //生产缓存数组
                if( ! array_key_exists($page->page_id,$cacheArrayPage)){
                    $cacheArrayPage[$page->page_id] = $objPage->id;
                }

            }
        }

        unset($ret);
        $countDevice = 0;
        $bid = 0;
        while(True){
            $ret = $this->syncDevice($appid,$appsecret,$bid);
            $devices = $ret->devices;
            $bid = $bid + count($devices);
            if( ! $devices){
                break;
            }
            //开始同步设备
            foreach($devices as $device){
                $objWxDevice = WeixinDevice::where('device_id',$device->device_id)->first();
                if( ! $objWxDevice){
                    $objWxDevice = new WeixinDevice();
                    $objWxDevice->uuid = $device->uuid;
                    $objWxDevice->major = $device->major;
                    $objWxDevice->minor = $device->minor;
                    $objWxDevice->comment = $device->comment;
                    $objWxDevice->poi_id = $device->poi_id;
                    $objWxDevice->wx_mp_id = $mp_id;
                    $objWxDevice->device_id = $device->device_id;
                    $objWxDevice->apply_id = $device->device_id;
                    $countDevice++;
                }
                $objWxDevice->status = $device->status;
                $objWxDevice->save();

                //处理页面-设备关系
                if( ! empty($device->page_ids)){
                    $arrPageIds = explode(',',$device->page_ids);
                    foreach($arrPageIds as $pageId){

                        $devicePage = DevicePage::where('wx_device_id',$objWxDevice->id)->where('wx_page_id',$cacheArrayPage[$pageId])->first();
                        if( ! $devicePage){
                            $devicePage = new DevicePage();
                            $devicePage->wx_device_id = $objWxDevice->id;
                            $devicePage->wx_page_id = $cacheArrayPage[$pageId];
                            $devicePage->save();
                        }
                    }
                }
            }

        }

        return ['count_page'=>$countPage,'count_device'=>$countDevice];

    }
}