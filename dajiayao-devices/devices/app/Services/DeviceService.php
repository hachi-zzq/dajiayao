<?php namespace Dajiayao\Services;

use Dajiayao\Library\Weixin\DeviceIdentifier;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinPage;
use Illuminate\Support\Facades\Session;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/13
 */

class DeviceService extends BaseController
{

    /**
     * @param DeviceIdentifier $deviceIdentifier
     * @return bool
     */
    public function create(DeviceIdentifier $deviceIdentifier)
    {
        $device = new Device();
        foreach ($deviceIdentifier as $k=>$v) {
            $device->$k = $v;
        }

        $device->save();

        return $device->$id;
    }


    /**
     * @param Device $device, 数据库实体
     * @param DeviceIdentifier $deviceIdentifier ，自己构建实体
     * @return bool
     */
    public function update(Device $device,DeviceIdentifier $deviceIdentifier)
    {
        foreach($deviceIdentifier as $k=>$v){
            $device->$k = $v;
        }
        return $device->save();
    }


    /**
     * @param Device $device，数据库device对象
     * @throws \Exception
     */
    public function delete(Device $device)
    {
        $device->delete();
    }

    /**
     * 申请微信设备
     * @param ShakeAroundClient $shakeAroundClient
     * @param $quantity
     * @param $apply_reason
     * @param $comment
     * @param $poiId
     * @author zhengqian@dajiayao.cc
     */
    public function applyDeviceOnline(ShakeAroundClient $shakeAroundClient,$quantity, $apply_reason, $comment=null, $poiId=null,$appid=null,$appsecret=null)
    {
        $accessToken = $this->getWeixinToken($appid,$appsecret);
        $ret = $shakeAroundClient->applyDeviceId($quantity,$apply_reason,$comment,$poiId,$accessToken);
        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,90000));
        }
        return $ret->data;
    }


    /**
     * 更新微信端设备信息
     * @author zhengqian@dajiayao.cc
     */
    public function updateWeixinDevice(DeviceIdentifier $deviceIdentifier,ShakeAroundClient $shakeAroundClient,$comment,$appid=null,$appsecret=null)
    {
        $accessToken = $this->getWeixinToken($appid,$appsecret);
        $ret = $shakeAroundClient->updateDeviceComment($comment,$deviceIdentifier,$accessToken);


        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,90000));
        }


    }

    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param Device $device ,自己构建实体
     * @param array $page_ids ，要绑定的页面id,主键id
     * @param int $bind
     * @param int $append
     * @throws \Exception
     */
    public function bindPage(ShakeAroundClient $shakeAroundClient,WeixinDevice $device,array $page_ids,$bind=1,$append=1,$appid=null,$appsecret=null)
    {


        //TODO 检查 wx_mp_id 是否设备-页面一一致
        $token = $this->getWeixinToken($appid,$appsecret);
        if($token == NULL){
            throw(new \Exception("weixin get token error",90000));
        }

        //page_ids转变为wx_page_ids
        foreach ($page_ids as $k=>&$v) {
            $wx_page_ids[$k] = WeixinPage::find($v)->page_id;
        }

        $ret = $shakeAroundClient->bindPage(new DeviceIdentifier($device->device_id,$device->uuid,$device->major,$device->minor),$wx_page_ids,(int)$bind,(int)$append,$token);
        if($ret->errcode != 0){
            throw(new \Exception('weixin error:'.$ret->errmsg,90000));
        }



        //本地数据库记录
        if($append==0){
            DevicePage::where('wx_device_id',$device->id)->delete();
        }

        foreach($page_ids as $id){
            if($bind == 1){
                $devicePage = new DevicePage();
                $devicePage->wx_device_id = $device->id;
                $devicePage->wx_page_id = $id;
                $devicePage->save();
            }elseif($bind == 0){
                DevicePage::where('wx_device_id',$device->id)->where('wx_page_id',$id)->delete();
            }

        }
    }




    /**
     * 根据sn获取设备id
     * @param $sn
     * @author zhengqian@dajiayao.cc
     */
    public function getDeviceBySn($sn)
    {
        $dev = Device::where('sn',$sn)->first();
        if( ! $dev){
            throw(new \Exception(24001,sprintf("the device sn: %s not found",$sn)));
        }

        return $dev;
    }

    /**
     * @设置跳转
     * @param WeixinDevice $weixinDevice
     * @param $url
     * @param $name
     * @return bool|int
     * @author zhengqian@dajiayao.cc
     */
    public function setRedirect(WeixinDevice $weixinDevice,$url,$name)
    {
        $weixinDevice->redirect_url = $url;
        $weixinDevice->redirect_name = $name;

        return $weixinDevice->save();
    }

    /**
     * 解除绑定
     * @param array $ids
     * @return mixed
     * @author zhengqian@dajiayao.cc
     */
    public function unsetRedirect(array $ids)
    {
        return WeixinDevice::whereIn('id',$ids)->update([
            'redirect_name'=>'',
            'redirect_url'=>''
        ]);
    }





}