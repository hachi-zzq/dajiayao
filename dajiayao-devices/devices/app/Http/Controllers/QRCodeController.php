<?php
namespace Dajiayao\Http\Controllers;
use Dajiayao\Model\Device;


/**
 * 二维码管理
 * Class PageController
 * @package Dajiayao\Http\Controllers
 */
class QRCodeController extends Controller
{

    public function __construct()
    {

    }

    /**
     * 处理用户扫描二维码后的操作
     * @author zhengqian@dajiayao.cc
     */
    public function urlHandler()
    {
        $inputData = \Input::only('sn');
        if( ! array_key_exists('sn',$inputData)){
            return "sn not found";
        }

        $dev = Device::getDeviceBySn($inputData['sn']);

        if( ! $dev){
            return sprintf("sn: %s not found",$inputData['sn']);
        }

        if( ! $wxDevice = $dev->weixinDevice){
            return 'sn not bind weixin ID';
        }

        $url = $wxDevice->mp->app->device_url;
        if( ! $url){
            return $inputData['sn'];
        }

        return redirect($url);


    }

}