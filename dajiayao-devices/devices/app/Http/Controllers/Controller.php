<?php namespace Dajiayao\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Config;
use Session;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    public function __construct() {
        if (!Session::has("wx_appid")) {
            Session::put("wx_appid", Config::get("weixin.ya_yao.appid"));
        }

        if (!Session::has("wx_appsecret")) {
            Session::put("wx_appsecret", Config::get("weixin.ya_yao.appsecret"));
        }
    }

}
