<?php
namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Model\App;
use Illuminate\Http\Request;
use J20\Uuid\Uuid;
use Dajiayao\Library\Help\RestHelp;

/**
 * 大家摇 Restful 授权控制
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/11
 */

class AuthController extends BaseController
{

    /**
     * 获取token
     * @param null
     * @return mixed
     * @author zhengqian.zhu
     */
    public function getToken(Request $request)
    {
        $request = $request->only('appid','t','sign');
        $validator = \Validator::make($request,array(
            'appid'=>'required',
            't'=>'required',
            'sign'=>'required'
        ));

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $app = App::where("app_id",$request['appid'])->first();
        if( ! $app){
            return RestHelp::encodeResult("21001","appId not found");
        }



        $appsecret = $app->app_secret;

        $md5Sign = $this->md5Str($request['appid'],$appsecret,$request['t']);

        if($request['sign'] != $md5Sign){
            return RestHelp::encodeResult("21002","sign not correct");
        }

        $day = new \DateTime();
        $day->modify("+2 hours");
        $app->expire_at = $day->format("Y-m-d H:i:s");


        //如果存在未过期的token，直接返回token
        if( ! $app->access_token ||  $app->expire_at <= date("Y-m-d H:i:s")){
            $app->access_token = Uuid::v4(false);
        }
        $app->save();

        return RestHelp::success(['access_token'=>$app->access_token,'expires_in'=>7200]);
    }

    /**
     * token 加密规则
     * @param $appid
     * @param $secret
     * @param $time
     * @return string
     */
    public function md5Str($appid,$secret,$time)
    {
        return md5(trim($appid).trim($secret).trim($time));
    }





}