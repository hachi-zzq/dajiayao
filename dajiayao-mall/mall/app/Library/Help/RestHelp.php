<?php namespace Dajiayao\Library\Help;

use Dajiayao\Model\RestLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/14
 */

class RestHelp
{


    const VERSION = '1.0';

    /**
     * 参数不合法 统一返回格式
     * @param null $message
     * @return string
     */
    public static function parametersIllegal($message=null)
    {
        return self::encodeResult(30000,$message);
    }


    /**
     * 正确的返回统一格式
     * @param $data
     * @return string
     */
    public static function success($data=null)
    {
        return self::encodeResult(10000,'success',$data);
    }


    public static function weixinErr($wxErrNo,$wxErrMsg)
    {
        return self::encodeResult(90000,"weixin error [err_no:$wxErrNo,err_msg:$wxErrMsg]");
    }


    /**
     * 统一返回格式
     * @param $msgcode
     * @param null $message
     * @param null $data
     * @return string
     */
    public static function encodeResult($msgcode, $message = NULL, $data = NULL)
    {
        if($data == null){
            $data = new \stdClass();
        }

        $log = new RestLog();
        $log->request = json_encode(Request::all());
        $log->request_route = Route::currentRouteName();
        $log->response = json_encode($data);
        $log->msgcode = $msgcode;
        $log->message = $message;
        $log->client_ip = Request::getClientIp();
        $log->client_useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;

        $log->save();

        $result = array(
            "rest_id"=>$log->id,
            'msgcode' => $msgcode,
            'message' => $message,
            'data' => $data,
            'version' => self::VERSION,
            'servertime' => time()
        );

        return \Response::json($result);
    }
}