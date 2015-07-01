<?php
namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Weixin\Page;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\WeixinPage;
use Dajiayao\Services\PageService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use J20\Uuid\Uuid;


/**
 * 大家摇 Restful 页面类
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/11
 */

class PageController extends BaseController
{
    protected $inputData;

    public function __construct()
    {
        $input = file_get_contents("php://input");
        $this->inputData = !empty($input) ? $input : "{}";
    }


    /**
     * 申请页面
     * @param PageService $pageService
     * @param ShakeAroundClient $shakeAroundClient
     * @param WeixinClient $weixinClient
     * @return string
     */
    public function create(PageService $pageService,ShakeAroundClient $shakeAroundClient)
    {
        $requestData = json_decode($this->inputData,true);

        $validator = Validator::make($requestData,[
            'title'=>'required|max:6',
            'description'=>'required|max:7',
            'icon_url'=>'required',
            'url'=>'',
            'comment'=>'',

        ]);
        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $guid = Uuid::v4(false);
        //TODO 检查各个字段长度
        $arrPage = [
            'title'=>$requestData['title'],
            'description'=>$requestData['description'],
            'comment'=>isset($requestData['comment']) ? $requestData['comment'] : '',
            'icon_url'=>$requestData['icon_url'],
            'url'=> Config::get('app.url').str_replace('GUID',$guid,Config::get("weixin.callback_url"))
        ];



        $appid =  Session::get('wx_appid');
        $appsecret =  Session::get('wx_appsecret');

        $page = new Page($arrPage);

        try{
            $page->page_url = $page->url;
            $pageId = $pageService->applyPageOnline($shakeAroundClient,$page,$appid,$appsecret);
        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        $page->page_id = $pageId;
        unset($page->page_url);
        $page->url = empty($requestData['url']) ? "" : $requestData['url'];
        $page->guid = $guid;
        $page->wx_mp_id = Session::get('wx_mp_id');
        $id = $pageService->create($page);

        return RestHelp::success(['page_id'=>$id]);
    }


    /**
     * @param PageService $pageService
     * @param ShakeAroundClient $shakeAroundClient
     * @param null $pageId
     * @return string
     */
    public function update(PageService $pageService,ShakeAroundClient $shakeAroundClient,$pageId=null)
    {

        if( ! $pageId){
            return RestHelp::parametersIllegal("page_id is required");
        }

        $objPage = WeixinPage::find($pageId);
        if( ! $objPage){
            return RestHelp::encodeResult(23001,'page not found');
        }

        $requestData = json_decode($this->inputData,true);
        $validator = Validator::make($requestData,[
            'title'=>'',
            'description'=>'',
            'icon_url'=>'',
            'url'=>'',
            'comment'=>'',

        ]);
        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $arrPage = array();
        $arrPage['page_id'] = $objPage->page_id;
        $arrPage['title'] = isset($requestData['title']) ? $requestData['title'] : $objPage->title;
        $arrPage['description'] = isset($requestData['description']) ? $requestData['description'] : $objPage->description;

        if(isset($requestData['icon_url'])){
            try{
                $iconUrl = $pageService->converToUrlOnline($shakeAroundClient,$requestData['icon_url']);
            }catch (\Exception $e){
                return RestHelp::encodeResult($e->getMessage(),$e->getCode());
            }
        }


        $arrPage['icon_url'] = isset($requestData['icon_url']) ? $iconUrl : $objPage->icon_url;
        $arrPage['url'] = isset($requestData['url']) ? $requestData['url'] : $objPage->url;
        $arrPage['comment'] = isset($requestData['comment']) ? $requestData['comment'] : $objPage->comment;

        $page = new Page($arrPage);


        $page->page_url = $page->url;

        try{
            $pageService->updatePageOnline($shakeAroundClient,$page);

        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        unset($page->page_url);
        try{
            $pageService->update($objPage,$page);
        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        return RestHelp::success(['page_id'=>$objPage->id]);

    }


    /**
     * @param ShakeAroundClient $shakeAroundClient
     * @param PageService $pageService
     * @return string
     */
    public function delete(ShakeAroundClient $shakeAroundClient,PageService $pageService)
    {
        $resquetData = json_decode($this->inputData,true);
        if( ! isset($resquetData['page_ids'])){
            return RestHelp::parametersIllegal("page_ids is required");
        }

        if( ! is_array($resquetData['page_ids'])){
            return RestHelp::parametersIllegal("page_ids is not array");
        }

        foreach ($resquetData['page_ids'] as $id) {
            if(!WeixinPage::find($id)){
                return RestHelp::encodeResult(23002,sprintf("page id %s not found",$id));
            }
        }

        try{
            $pageService->deletePageOnline($shakeAroundClient,$resquetData['page_ids']);
        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        $pageService->delete($resquetData['page_ids']);

        return RestHelp::success();

    }

    /**
     * @param PageService $pageService
     * @param ShakeAroundClient $shakeAroundClient
     * @param null $pageId
     * @return string
     */
    public function bindDevice(PageService $pageService,ShakeAroundClient $shakeAroundClient,$pageId=null)
    {
        if( ! $pageId){
            return RestHelp::parametersIllegal("sn is required");
        }

        $page = WeixinPage::find($pageId);
        if( ! $page){
            return RestHelp::encodeResult(23001,"page not found");
        }

        $requestData = json_decode($this->inputData,true);
        $pageIds = $requestData['sn'];
        if( ! $pageIds or !is_array($requestData['sn'])){
            return RestHelp::encodeResult(24002,'sn must be arrary');
        }

        $validator = Validator::make($requestData,[
            'sn'=>'required',
            'bind'=>'required|boolean',
            'append'=>'required|boolean'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        foreach ($requestData['sn'] as $k=>&$v) {
            $device = Device::where('sn',$v)->first();
            if( ! $device){
                return RestHelp::encodeResult(24003,sprintf("sn %s not found",$v));
            }
            $requestData['sn'][$k] = $device->wx_device_id;
        }

        try{
            $pageService->bindDevice($shakeAroundClient,$page,$requestData['sn'],$requestData['bind'],$requestData['append']);

        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        return RestHelp::success();

    }


    /**
     * 页面具体信息
     * @param $pageId
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function getInfo($pageId)
    {
        if( ! $pageId){
            return RestHelp::parametersIllegal("page id is required");
        }
        $objPage = WeixinPage::find($pageId);
        if( ! $objPage){
            return RestHelp::encodeResult(23001,sprintf("page id is %s not found",$pageId));
        }

        $retPage = [
            'title'=>$objPage->title,
            'description'=>$objPage->description,
            'icon_url'=>$objPage->icon_url,
            'url'=>$objPage->url,
            'comment'=>$objPage->comment
        ];
        $retDevice = array();
        $devicePage = DevicePage::where('wx_page_id',$pageId)->get();
        foreach($devicePage as $dp){
            $device = $dp->device->weixinDevice;
            foreach($device as $d){
                array_push($retDevice,[
                    'sn'=>$d->sn,
                    'device_id'=>$d->wx_device_id,
                    'comment'=>$d->comment,
                    'status'=>$d->status
                ]);
            }
        }
        return RestHelp::success(['page'=>$retPage,'device'=>$retDevice]);
    }


}