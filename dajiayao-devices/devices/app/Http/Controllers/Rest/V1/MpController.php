<?php
namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Library\Weixin\Mp;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Services\MpService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Dajiayao\Library\Help\RestHelp;

/**
 * 大家摇 Restful 微信类
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/11
 */

class MpController extends BaseController
{

    protected $inputData;

    public function __construct()
    {
        $input = file_get_contents("php://input");
        $this->inputData = !empty($input) ? $input : "{}";
    }


    /**
     * 微信公众账号添加
     * @return string
     */
    public function create(MpService $mpService)
    {
        $resquetData = json_decode($this->inputData,true);
        $validator = Validator::make($resquetData,[
            'name'=>'required',
            'appid'=>'required',
            'appsecret'=>'required',
            'mp_id'=>'required',
            'comment'=>''
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $id = $mpService->create(new Mp($resquetData['name'],$resquetData['mp_id'],$resquetData['appid'],$resquetData['appsecret'],$resquetData['comment']));

        return RestHelp::success(['wx_id'=>$id]);
    }





    /**
     * $删除
     * @param MpService $mpService
     * @param $wxId
     * @return string
     */
    public function delete(MpService $mpService,$wxId)
    {
        $mp = WeixinMp::find($wxId);
        if( ! $mp){
            return RestHelp::encodeResult(22001,'weixin not found');
        }

        $mpService->delete($mp);
        return RestHelp::success();
    }


}