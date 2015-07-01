<?php
namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Services\StatisticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Dajiayao\Library\Help\RestHelp;
use Illuminate\Support\Facades\Session;


/**
 * 大家摇 Restful 设备类
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/11
 */

class StatisticsController extends BaseController
{

    protected $request;
    protected $statisticService;

    public function __construct(Request $request,StatisticService $statisticService)
    {
        $this->request = $request;
        $this->statisticService = $statisticService;
    }

    /**
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function deviceStatistics()
    {
        $inputData = $this->request->only(['device_id','begin_date','end_date']);
        $validator = Validator::make($inputData,[
            'device_id'=>'required|integer',
            'begin_date'=>'required|integer',
            'end_date'=>'required|integer'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $appid =  Session::get('wx_appid');
        $appsecret =  Session::get('wx_appsecret');

        try{
            $ret = $this->statisticService->deviceStatistic($inputData['device_id'],$inputData['begin_date'],$inputData['end_date'],$appid,$appsecret);

        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        return RestHelp::success($ret);

    }


    /**
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function pageStatistics()
    {
        $inputData = $this->request->only(['page_id','begin_date','end_date']);
        $validator = Validator::make($inputData,[
            'page_id'=>'required|integer',
            'begin_date'=>'required|integer',
            'end_date'=>'required|integer'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $appid =  Session::get('wx_appid');
        $appsecret =  Session::get('wx_appsecret');

        try{
            $ret = $this->statisticService->pageStatistic($inputData['page_id'],$inputData['begin_date'],$inputData['end_date'],$appid,$appsecret);

        }catch (\Exception $e){
            return RestHelp::encodeResult($e->getCode(),$e->getMessage());
        }

        return RestHelp::success($ret);
    }

}