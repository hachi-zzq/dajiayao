<?php namespace Dajiayao\Services;
use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Weixin\DeviceIdentifier;
use Dajiayao\Library\Weixin\Page;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\WeixinPage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use J20\Uuid\Uuid;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/13
 */

class PageService extends BaseController
{

    /**
     * @param Page $page,自己构建的实体
     * @return mixed
     */
    public function create(Page $page){
        $newPage = new WeixinPage();
        foreach($page as $k=>$attr){
            $newPage->$k = $attr;
        }
        $newPage->save();

        return $newPage->id;
    }


    /**
     * @param WeixinPage $pageEntity，数据库要跟新的实体
     * @param Page $page，本地自己构建的实体
     * @return bool
     */
    public function update(WeixinPage $pageEntity,Page $page)
    {
        foreach($page as $k=>$attr){
            $pageEntity->$k = $attr;
        }

        return $pageEntity->save();

    }

    /**
     * @param array $ids,本地数据主键id
     * @return mixed
     */
    public function delete(array $ids)
    {
        DevicePage::whereIn('wx_page_id',$ids)->delete();
        return WeixinPage::whereIn('id',$ids)->delete();

    }




    /**
     * 将上传地址带有local:// http:// 转成微信地址
     * @param $rawPathStr
     */
    public function converToUrlOnline(ShakeAroundClient $shakeAroundClient,$materialFile,$appid=null,$appsecret=null)
    {
        //TODO 判断图片路径是否是微信域名，如果符合则不进行下载
        if(preg_match("/^local:\/\/(.*)/",$materialFile,$match)){
            $materialFile = $match[1];

        }elseif(preg_match("/^http:\/\/(.*)/",$materialFile,$match)){
            $dir = public_path('upload/'.date("Ymd"));
            if( ! is_dir($dir)){
                mkdir($dir,0777,true);
            }
            $materialFile = $shakeAroundClient->downloadFile($materialFile,public_path("upload/".date("Ymd").'/'.Uuid::v4(false).'.png'));

        }else{
            throw new \Exception("file format illegal");
        }
        $ret = $shakeAroundClient->addMaterial($materialFile,$this->getWeixinToken($appid,$appsecret));
        if( $ret->errcode != 0){
            throw new \Exception($ret->errmsg,90000);
        }
        return $ret->data->pic_url;

    }

    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param Page $Page 、、自己构建的page实体
     * @param null $wxId 、、用来申请页面的微信id号，本地主键id
     * @return mixed
     * @throws \Exception
     */
    public function applyPageOnline(ShakeAroundClient $shakeAroundClient,Page $Page,$appid=null,$appsecret=null)
    {

        $accessToken = $shakeAroundClient->applyAccessToken($appid,$appsecret);
        if($accessToken == NULL){
            throw(new \Exception("weixin get token error"));
        }

        $Page->icon_url = $this->converToUrlOnline($shakeAroundClient,$Page->icon_url,$appid,$appsecret);

        $ret = $shakeAroundClient->addPage($Page,$accessToken);

        if($ret->errcode != 0){
            throw(new \Exception($ret->errmsg));
        }

        return $ret->data->page_id;

    }

    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param Page $Page,自己构建的实体
     * @return mixed
     * @throws \Exception
     */
    public function updatePageOnline(ShakeAroundClient $shakeAroundClient,Page $Page,$appid=null,$appsecret=null)
    {

        $accessToken = $this->getWeixinToken($appid,$appsecret);
        $ret = $shakeAroundClient->updatePage($Page,$accessToken);

        if($ret->errcode != 0){
            throw(new \Exception($ret->errmsg,$ret->errcode));
        }

        return $ret->data->page_id;

    }


    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param array $ids,本地主键id
     * @throws \Exception
     */
    public function deletePageOnline(ShakeAroundClient $shakeAroundClient,array $ids,$appid=null,$appsecret=null)
    {

        foreach($ids as $v){
            if(!WeixinPage::find($v)){
                throw(new \Exception(sprintf('page id %s not found',$v),23001));
            }
        }
        $accessToken = $this->getWeixinToken($appid,$appsecret);

        if($accessToken == NULL){
            throw(new \Exception("weixin get token error",90000));
        }

        foreach ($ids as $k=>&$v) {
            $ids[$k] = WeixinPage::find($v)->page_id;
        }

        $ret = $shakeAroundClient->deletePageByIds(['page_ids'=>$ids],$accessToken);

        if($ret->errcode != 0){
            throw(new \Exception('weixin error'.$ret->errmsg,90000));
        }
    }


    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param WeixinPage $weixinPage,本地数据库页面实体
     * @param array $wx_device_ids ，本地数据库库主键设备id
     * @param int $bind
     * @param int $append
     * @throws \Exception
     */
    public function bindDevice(ShakeAroundClient $shakeAroundClient,WeixinPage $weixinPage,array $wx_device_ids,$bind=1,$append=1,$appid=null,$appsecret=null)
    {

        //TODO 检查 wx_mp_id 是否设备-页面一一致
        $token = $this->getWeixinToken($appid,$appsecret);

        foreach ($wx_device_ids as $id) {
            $wx_device = WeixinDevice::find($id);
            if( ! $wx_device){
                throw(new \Exception(sprintf("device id %s not found",$id),24001));
            }

            $ret = $shakeAroundClient->bindPage(
                new DeviceIdentifier($wx_device->device_id,$wx_device->uuid,$wx_device->major,$wx_device->minor),
                [$weixinPage->page_id],
                (int)$bind,
                (int)$append,
                $token
            );

            if($ret->errcode != 0){
                throw(new \Exception('weixin error'.$ret->errmsg,90000));
            }
        }

        //本地数据库记录
        if($append == 0){
            DevicePage::where('wx_page_id',$weixinPage->id)->delete();
        }

        foreach($wx_device_ids as $id){
            if($bind == 1){

                $devicePage = new DevicePage();
                $devicePage->wx_device_id = $id;
                $devicePage->wx_page_id = $weixinPage->id;
                $devicePage->save();
            }elseif($bind == 0){
                DevicePage::where('wx_device_id',$id)->where('wx_page_id',$weixinPage->id)->delete();
            }

        }


    }


}