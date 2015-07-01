<?php
namespace Dajiayao\Http\Controllers\Rest\V1;
use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Services\SharkService;
use Illuminate\Http\Request;


/**
 * 大家摇 Restful 页面类
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/11
 */

class SharkController extends BaseController
{

    /**
     * 获取sharkinfo，根据token
     * @param Request $request
     * @param SharkService $sharkService
     * @return string
     */
    public function getinfo(Request $request,SharkService $sharkService)
    {
        $requestData =  $request->only('ticket');
        try{
            $ret = $sharkService->getInfoFromRedis($requestData['ticket']);
        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        return RestHelp::success($ret);
    }




}