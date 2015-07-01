<?php
namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Weixin\DeviceIdentifier;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinPage;
use Dajiayao\Services\DeviceService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 大家摇 Restful 设备类
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/11
 */

class DeviceController extends BaseController
{


    protected $inputData;

    public function __construct()
    {
        $input = file_get_contents("php://input");
        $this->inputData = !empty($input) ? $input : "{}";
    }

    public function index()
    {
        $requestData = Request::only('pagesize','bid','device_id');

        $pagesize = isset($requestData['pagesize']) ? (int)$requestData['pagesize'] :20;
        $bid = isset($requestData['bid']) ? (int)$requestData['bid'] : 0;

        $dev = Device::where('id','>',$bid);

        if(isset($requestData['device_id'])){
            $dev = $dev->where('wx_device_id',$requestData['device_id']);
        }

        $dev = $dev->paginate($pagesize);

        $arrRet = array();
        foreach($dev as $d){
            array_push($arrRet,['id'=>$d->id,'sn'=>$d->sn,'wx_device_id'=>$d->wx_device_id,'comment'=>$d->comment,'status'=>$d->status]);
        }

        $dev = $dev->toArray();
        return RestHelp::success(['total'=>$dev['total'],'per_page'=>$dev['per_page'],'current_page'=>$dev['current_page'],'device'=>$arrRet]);
    }

    /**
     * @设备绑定页面
     * @param DeviceService $deviceService
     * @param ShakeAroundClient $shakeAroundClient
     * @param string $sn
     * @return string
     */
    public function bindPage(DeviceService $deviceService,ShakeAroundClient $shakeAroundClient,$sn='')
    {
        if( ! $sn){
            return RestHelp::parametersIllegal("sn is required");
        }

        $device = Device::where("sn",$sn)->first();
        if( ! $device){
            return RestHelp::encodeResult(24001,sprintf("sn %s not found",$sn));
        }

        $requestData = json_decode($this->inputData,true);
        $pageIds = $requestData['page_ids'];
        if( ! $pageIds or !is_array($requestData['page_ids'])){
            return RestHelp::encodeResult(24002,'page_ids must be arrary');
        }

        foreach ($pageIds as $pid) {
            if( ! WeixinPage::find($pid)){
                return RestHelp::encodeResult(23001,sprintf("page id :%s not found",$pid));
            }
        }


        $validator = Validator::make($requestData,[
            'page_ids'=>'required',
            'bind'=>'required|boolean',
            'append'=>'required|boolean'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        try{
            $deviceService->bindPage($shakeAroundClient,WeixinDevice::find($device->wx_device_id),$requestData['page_ids'],$requestData['bind'],$requestData['append']);

        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        return RestHelp::success();


    }


    /**
     * 根据sn获取设备id
     * @param DeviceService $deviceService
     * @param $sn
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function getDevBySn(DeviceService $deviceService,$sn)
    {
        try{
            $dev = $deviceService->getDeviceBySn($sn);
        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        $wxDeviceId = $dev->wx_device_id;

        $arrPage = array();
        $devicePage = DevicePage::where('wx_device_id',$wxDeviceId)->get();

        foreach ($devicePage as $v) {
            $page = $v->page;
            if( ! $page){
                return RestHelp::encodeResult(23001,sprintf("page id: %s not found",$v->page_id));
            }
            array_push($arrPage,[
                'page_id'=>$page->id,
                'title'=>$page->title,
                'description'=>$page->description,
                'icon_url'=>$page->icon_url,
                'url'=>$page->url,
                'comment'=>$page->comment
            ]);
        }

        return RestHelp::success(['device_id'=>$wxDeviceId,'page'=>$arrPage]);
    }


    /**
     * 查询某个设备的具体信息
     * @param $deviceId
     * @author zhengqian@dajiayao.cc
     */
    public function getInfo($wxDeviceId)
    {
        if(!$wxDeviceId){
            return RestHelp::parametersIllegal("device id is required");
        }

        $devices = Device::where('wx_device_id',$wxDeviceId)->get();

        if( ! $devices){
            return RestHelp::encodeResult(24001,sprintf("device id: %s not foun",$wxDeviceId));
        }

        $arrDevice = array();
        foreach ($devices as $v) {
            array_push($arrDevice,['sn'=>$v->sn,'comment'=>$v->comment,'status'=>$v->status]);
        }
        unset($v);
        $arrPage = array();
        $devicePage = DevicePage::where('wx_device_id',$wxDeviceId)->get();

        foreach ($devicePage as $v) {
            $page = $v->page;
            if( ! $page){
                return RestHelp::encodeResult(23001,sprintf("page id: %s not found",$v->page_id));
            }
            array_push($arrPage,[
                'page_id'=>$page->id,
                'title'=>$page->title,
                'description'=>$page->description,
                'icon_url'=>$page->icon_url,
                'url'=>$page->url,
                'comment'=>$page->comment
            ]);
        }

        return RestHelp::success(['device'=>$arrDevice,'page'=>$arrPage]);
    }


    /**
     * 更新设备信息
     * @param ShakeAroundClient $shakeAroundClient
     * @param DeviceService $deviceService
     * @param null $sn
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function updateComment(ShakeAroundClient $shakeAroundClient,DeviceService $deviceService,$sn=null)
    {
        if( ! $sn){
            return RestHelp::parametersIllegal("sn is required");
        }

        $requestData = json_decode($this->inputData,true);
        if( ! isset($requestData['comment'])){
            return RestHelp::parametersIllegal("comment is required");
        }
        $objDevice = Device::where('sn',$sn)->first();

        if( ! $objDevice){
            return RestHelp::encodeResult(24001,sprintf("sn: %s not foun",$sn));
        }

        try{
            $dev = new DeviceIdentifier($objDevice->weixinDevice->device_id,$objDevice->weixinDevice->uuid,$objDevice->weixinDevice->major,$objDevice->weixinDevice->minor);
            $deviceService->updateWeixinDevice($dev,$shakeAroundClient,$requestData['comment']);

        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }
        $objDevice->comment = $requestData['comment'];


        $objDevice->save();

        return RestHelp::success();

    }

    /**
     * 更新设备的地理位置
     * @param DeviceService $deviceService
     * @param $sn
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function setLocation(DeviceService $deviceService,$sn)
    {
        if( ! $sn){
            return RestHelp::parametersIllegal('sn is required');
        }


        $dev = Device::getDeviceBySn($sn);

        if( ! $dev){
            return RestHelp::encodeResult(24001,sprintf("sn:%s not found",$sn));

        }

        $inputData = json_decode($this->inputData,true);

        $validator = Validator::make($inputData,[
            'longitude'=>'required',
            'latitude'=>'required',
            'address'=>'required',
            'location'=>'required'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $dev->longitude = $inputData['longitude'];
        $dev->latitude = $inputData['latitude'];
        $dev->address = $inputData['address'];
        $dev->position = $inputData['position'];
        $dev->save();

        return RestHelp::success();
    }

}