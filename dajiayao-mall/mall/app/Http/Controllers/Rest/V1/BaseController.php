<?php namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Model\Payment;
use Dajiayao\Model\Order;
use Dajiayao\Model\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * 前台基类
 * Class BaseController
 * @package Dajiayao\Http\Controllers
 */

class BaseController extends Controller
{

    protected $inputData;

    protected $buyerId;

    public function __construct(Request $request)
    {
        $this->inputData = $request;
        $buyerId = Session::get('buyer_id');

        $this->buyerId = $buyerId;

        if( ! $buyerId){
            RestHelp::encodeResult(60000,"session expire !!");

        }

//        $this->buyerId = is_null(Session::get('buyer_id')) ? 1 : Session::get('buyer_id');

        $this->checkOrderExpire();
    }


    /** 检查长时间未支付的订单
     * @author zhengqian@dajiayao.cc
     */
    public function checkOrderExpire()
    {
        $paymentDuration = Setting::getByKey(Setting::KEY_ORDER_PAYMENT_DURATION)->value;

        $orders = Order::where('created_at','<',date("Y-m-d H:i:s",time()-3600*$paymentDuration))->where('payment_status',Order::PAY_STATUS_NO)->where("status",'!=',Order::STATUS_CLOSED)->get();
        foreach($orders as $order){
            $order->status  = Order::STATUS_CLOSED;
            $order->save();

            //恢复库存
            $orderItems = $order->orderItems;
            foreach ($orderItems as $orderItem) {
                $item = $orderItem->items;
                $item->stock += $orderItem->quantity;
                $item->save();
            }

        }

    }


}