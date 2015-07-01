<?php namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Library\Help\Tool;
use Dajiayao\Library\Mq\MQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

/**
 * Class WeixinController
 * @package Dajiayao\Http\Controllers
 */
class WeixinController extends BaseController
{

    protected $mq;

    public function __construct()
    {
        $this->mq = new MQ();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getToken(Request $request)
    {
        $input = $request->only('appid', 'appsecret');
        $validator = Validator::make($input, array(
            'appid' => 'required',
            'appsecret' => 'required'
        ));

        if ($validator->fails()) {
            return null;
        }
        try {
            return $this->mq->getWeixinAccessToken($input['appid'], $input['appsecret']);
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getJsAPITicket(Request $request)
    {
        $input = $request->only('appid', 'appsecret');
        $validator = Validator::make($input, array(
            'appid' => 'required',
            'appsecret' => 'required'
        ));

        if ($validator->fails()) {
            return null;
        }
        try {
            return $this->mq->getWeixinJsapiTicket($input['appid'], $input['appsecret']);
        } catch (\Exception $e) {
            return null;
        }
    }

}