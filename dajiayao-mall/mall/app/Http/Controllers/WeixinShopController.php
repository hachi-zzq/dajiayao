<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/6/5
 * Time: 16:58
 */

namespace Dajiayao\Http\Controllers;


use Dajiayao\Model\Order;
use Dajiayao\Model\OrderCommission;
use Dajiayao\Model\Payment;
use Dajiayao\Model\SellerCommission;
use Dajiayao\Model\Shop;
use Dajiayao\Model\PaymentLog;
use Dajiayao\Services\BuyerService;
use Dajiayao\Services\SellerService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;


class WeixinShopController extends BaseController
{

    protected $redis;
    public function __construct(BuyerService $buyerService,SellerService $sellerService)
    {
        $this->redis = Redis::connection();
        $this->buyerService = $buyerService;
        $this->sellerService = $sellerService;
    }


    public function test()
    {
        var_dump(Session::get('openid'));
    }

    /**
     * @param $shortId
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function index($shortId)
    {
        if( ! $shortId){
            return "shop id is required";
        }

        $shop = Shop::where("short_id",$shortId)->first();
        if( ! $shop){
            return "shop is not found";
        }


        try{
            $jsFileHash = [
                'vendor'=>hash_file('md2',public_path('assets/scripts/vendor.js')),
                'shop'=>hash_file('md2',public_path('assets/scripts/shop.js')),
                'shop_css'=>hash_file('md2',public_path('assets/stylesheets/shop.css')),
                'app'=>hash_file('md2',public_path('assets/stylesheets/app.css'))
            ];
        }catch (\Exception $e){
            $jsFileHash = [];
        }


        return view('shop.index')->with('shop_short_id',$shortId)->with('hash_file',$jsFileHash);

    }




    /**
     * 用于接收 p++ 支付结果的回调地址
     * @author zhengqian@dajiayao.cc
     */
    public function payCallBack()
    {
        $input = file_get_contents("php://input");
        \Log::info(sprintf("input: %s",$input));
        $objPayResult = json_decode($input);

        $payment = Payment::where('payment_number',$objPayResult->id)->first();
        if( ! $payment){
            \Log::info(sprintf("the payment_number : %s not found in db",$objPayResult->id));
            return ;
        }
        //写入支付日志
        $paymentLog = PaymentLog::where('payment_id',$payment->id)->first();
        if( ! $paymentLog){
            return;
        }

        $order = $payment->order;

        $paymentLog->respond_data = $input;
        $paymentLog->save();
        if($objPayResult->paid == true){
            //修改订单状态
            $order->payment_serial_number = $payment->serial_number;
            $order->payment_id = $payment->id;
            $order->payment_status = Order::PAY_STATUS_YES;
            $order->status = Order::STATUS_TO_DELIVER;
            $order->save();

            //支付状态
            $payment->status = Payment::PAYMENT_STATUS_SUCCESS;
            $payment->save();

            //佣金状态
            $commission = OrderCommission::where('order_id',$order->id)->first();

            if( ! $commission){
                return;
            }

            $commission->status = OrderCommission::STATUS_CONFIRMED;
            $commission->save();

            //seller commission
            $sellerCommission = SellerCommission::firstOrNew([
                'order_id'=>$order->id,
                'seller_id'=>$order->shop->seller_id,
                'amount'=>$commission->amount
            ]);
            $sellerCommission->status = SellerCommission::STATUS_CONFIRMED;
            $sellerCommission->save();

            //将销售额存入redis
            $orderItems = $order->orderItems;
            foreach($orderItems as $orderItem){
                $quantity = $orderItem->quantity;
                $this->redis->incrby("dajiayao:mall:item:sellcount:".$orderItem->item_id,$quantity);
                $wxUser = $orderItem->order->buyer->wxUser;

                $header = $wxUser->headimgurl;
                if($order->is_anonymous== 1 or ! $header){
                    continue;
                }
                $this->redis->sadd("dajiayao:mall:item:buyers:".$orderItem->item_id,json_encode(['name'=>$wxUser->nickname,'avatar'=>$header]));

            }

            //微信消息推送
            $this->buyerService->sendNewOrderMsg($order);
            $this->sellerService->sendPaidMsg($order);

        }elseif($objPayResult->paid == false){

            //支付状态
            $payment->status = Payment::PAYMENT_STATUS_FAIL;
            $payment->save();

        }else{
            return ;
        }

        return "success";
    }
}