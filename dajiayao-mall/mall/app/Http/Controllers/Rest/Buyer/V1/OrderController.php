<?php namespace Dajiayao\Http\Controllers\Rest\Buyer\V1;

use Dajiayao\Model\Buyer;
use Dajiayao\Library\Help\RestHelp;
use \Validator;
use Dajiayao\Model\Shop;
use Dajiayao\Library\Order\OrderHelper;
use Dajiayao\Model\Order;
use Dajiayao\Model\Payment;
use Dajiayao\Model\PaymentLog;

class OrderController extends BaseController
{


    /**
     * 重新支付
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function rePay()
    {

        $buyerId = $this->buyerId;
        $wxUser = Buyer::find($buyerId)->wxUser;
        if( ! $wxUser){
            return RestHelp::encodeResult(24000,"user illegality");
        }
        $inputData = $this->inputData->all();

        $validator = Validator::make($inputData,[
            'orderNumber'=>'required',
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $objOrder = Order::where('order_number',$inputData['orderNumber'])->first();

        $objShop = Shop::find($objOrder->shop_id);

        if( ! $objShop){
            return RestHelp::encodeResult(21000,sprintf("shop short id %s not found in db",$objOrder->shop_id));
        }

        //查找价格
        $itemTotal = $objOrder->item_total;
        $grandTotal = $objOrder->grand_total;

        $payment = new Payment();
        $payment->serial_number = OrderHelper::getPaymentSerialNumber(1);
        $payment->payment_number = '';
        $payment->order_id = $objOrder->id;
        $payment->order_number = $inputData['orderNumber'];
        $payment->buyer_id = $buyerId;
        $payment->amount = $itemTotal;
        $payment->channel = Payment::PAYMENT_CHANNEL_PXX;
        $payment->type = Payment::PAYMENT_TYPE_WX;
        $payment->status = Order::PAY_STATUS_NO;
        $payment->save();


        $subject = '';//32
        $body = '';//128

        foreach($objOrder->orderItems as $orderItem){
            $subject .= $orderItem->items->title."*".$orderItem->items->spec."*".$orderItem->quantity.",";
            $body .= $orderItem->items->title.$orderItem->items->spec.$orderItem->quantity;
        }

        try{

            \Pingpp\Pingpp::setApiKey('sk_live_3dKEivmziedjzitFhaHL7gYF');
            $ch = \Pingpp\Charge::create(
                array(
                    'order_no'  => $payment->serial_number,
                    'app'       => array('id' => 'app_XTOW5SXTWLGCGKef'),
                    'channel'   => 'wx_pub',
                    'amount'    => $grandTotal*100,
                    'client_ip' => $this->inputData->ip(),
                    'currency'  => 'cny',
                    'subject'   => mb_substr($subject,0,32),
                    'body'      => mb_substr($body,0,128),
                    'extra'=>array('open_id' =>$wxUser->openid)
                )
            );
        }catch (\Exception $e){
            return RestHelp::encodeResult(22003,$e->getMessage(),['orderNum'=>$inputData['orderNumber']]);
        }

        //写入paytmentLOg
        $paymentLog = new PaymentLog();
        $paymentLog->payment_id = $payment->id;
        $paymentLog->channel = Payment::PAYMENT_CHANNEL_PXX;
        $paymentLog->request_data = $ch;
        $paymentLog->respond_data = '';
        $paymentLog->save();


        //保存支付流水号
        $payment->payment_number = json_decode($ch)->id;
        $payment->save();



        return RestHelp::success([
            'orderNumber'=>$inputData['orderNumber'],
            'paymentNumber'=>$payment->serial_number,
            'charge'=>json_decode($ch,true)
        ]);

    }
}