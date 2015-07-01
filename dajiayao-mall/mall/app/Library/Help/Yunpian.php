<?php namespace Dajiayao\Library\Help;

use Illuminate\Support\Facades\Config;

class Yunpian
{

    public static function sendSmsCode($mobile, $code) {
        $send_url = "http://yunpian.com/v1/sms/send.json";
        $apikey = Config::get('app.yunpian.apikey');
        $tpl = Config::get('app.yunpian.tpl');
        $smsText = sprintf($tpl, $code);
        $postData = "apikey=$apikey&mobile=$mobile&text=$smsText";
        Tool::getCurl($send_url, 60, 'post', $postData);
        return true;
    }
}