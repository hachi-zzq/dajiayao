<?php namespace Dajiayao\Http\Controllers;

use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinPage;
use Dajiayao\Services\SharkService;
use Dajiayao\Services\WeixinUserService;
use Illuminate\Http\Request;
use \Exception;
use J20\Uuid\Uuid;

class SharkController extends Controller {

    /**
     * @取得所有参数
     * @param Request $request
     *
     */
    public function __construct(Request $request)
    {
        $this->input = $request->all();
    }

    /**
     * 所有中间处理逻辑，生产本地ticket
     * @param $pageId //页面id号
     * @param ShakeAroundClient $shakeAroundClient
     * @param SharkService $sharkService
     * @throws Exception
     */
	public function redirectCallback(ShakeAroundClient $shakeAroundClient,SharkService $sharkService)
    {

        $requestData = $this->input;
        if( ! array_key_exists('ticket',$requestData)){
            throw(new Exception("param miss ticket"));
        }

        if( ! array_key_exists('guid',$requestData)){
            throw(new Exception("param miss guid"));
        }



        $objPage = WeixinPage::where('guid',$requestData['guid'])->first();
        if( ! $objPage){
            return RestHelp::encodeResult(23001,sprintf(" guid %s not found",$requestData['guid']));
        }

        $uuid = Uuid::v4(false);

        //set into redis
        try{
            $sharkService->setInfoInRedis($shakeAroundClient,$uuid,$requestData['ticket'],0,$objPage->mp->appid,$objPage->mp->appsecret);
        }catch (Exception $e){
            echo $e->getMessage().$e->getCode();
            exit;
        }

        try{
            $ret = $sharkService->setInfoInDB($shakeAroundClient,$requestData['ticket'],0,$objPage->mp->appid,$objPage->mp->appsecret);

        }catch (Exception $e){
            echo $e->getMessage().$e->getCode();
            exit;
        }

        $appid = $objPage->mp->appid;
        $appsecret = $objPage->mp->appsecret;
        $sharkService->saveUserInfo(new WeixinUserService(),$ret->openid,$appid,$appsecret,$objPage->mp->mp_id,$objPage->mp->id);


        //判断该设备有没有设置重定向，如果设置了，直接跳转
        $wxDevice = WeixinDevice::where('uuid',$ret->uuid)->where('major',$ret->major)->where('minor',$ret->minor)->first();
        if( ! $wxDevice){
            return "device not found";
            exit;
        }

        $url = $wxDevice->redirect_url ? $wxDevice->redirect_url : $objPage->url;

        $url = preg_match("/\?/",$url) ? $url."&ticket=".$uuid : $url."?ticket=".$uuid;
        //redirect
        return redirect($url);

    }



}
