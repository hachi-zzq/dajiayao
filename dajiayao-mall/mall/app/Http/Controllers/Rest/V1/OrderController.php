<?php namespace Dajiayao\Http\Controllers\Rest\V1;

use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Order\OrderHelper;
use Dajiayao\Model\Buyer;
use Dajiayao\Model\BuyerAddress;
use Dajiayao\Model\Item;
use Dajiayao\Model\ItemType;
use Dajiayao\Model\OrderCommission;
use Dajiayao\Model\OrderItem;
use Dajiayao\Model\Payment;
use Dajiayao\Model\PaymentLog;
use Dajiayao\Model\SellerCommission;
use Dajiayao\Model\Setting;
use Dajiayao\Model\WxUserKv;
use Dajiayao\Services\OrderService;
use Dajiayao\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Dajiayao\Model\Shop;
use Dajiayao\Model\Order;


/**
 * Class BaseController
 * @package Dajiayao\Http\Controllers
 */

class OrderController extends BaseController
{

    protected $settingService;
    protected $orderService;

    public function __construct(Request $request,SettingService $settingService,OrderService $orderService)
    {
        $this->settingService = $settingService;
        $this->orderService = $orderService;

        parent::__construct($request);

    }


    /**
     * 生成订单
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function create()
    {


//        $inputData = $this->inputData->all();
        $buyerId = $this->buyerId;
        $wxUser = Buyer::find($buyerId)->wxUser;
        if( ! $wxUser){
            return RestHelp::encodeResult(24000,"user illegality");
        }
        $inputData = json_decode(file_get_contents("php://input"),true);

        $validator = Validator::make($inputData,[
            'shopShortId'=>'required',
            'items'=>'required',
            'deliverAddressId'=>'required',
            'paymentType'=>'required',
            "shopType"=>'',
            'orderNumber'=>'',
            "isAnonymous"=>'required|Boolean'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        if( ! isset($inputData['items']) or ! is_array($inputData['items'])){
            return RestHelp::encodeResult(23003,'item must be array');
        }

        $objShop = Shop::getShopByShort($inputData['shopShortId']);

        if( ! $objShop){
            return RestHelp::encodeResult(21000,sprintf("shop short id %s not found in db",$inputData['shopShortId']));
        }

        $deliver = BuyerAddress::find($inputData['deliverAddressId']);
        if( ! $deliver){
            return RestHelp::encodeResult(23001,sprintf("deliver address id: %s not found in db",$inputData['deliverAddressId']));
        }

        if( ! isset($inputData['orderNumber'])){
            $orderNumber = OrderHelper::getOrderSerialNumber();
        }else{
            $orderNumber = $inputData['orderNumber'];
        }

        $deliverAddressId = $inputData['deliverAddressId'];
        $shopId = $objShop->id;

        $itemTotal = 0;
        $postageFlag = 0;

        $shelfItems = $objShop->getItemsOnShelf();
        //检查商品合法性、库存等等
        foreach($inputData['items'] as $item){
            if( ! array_key_exists('id',$item) or ! array_key_exists('count',$item)){
                return RestHelp::encodeResult(23005,"items not correct");
            }
            $objItem = Item::find($item['id']);
            $itemTotal += $objItem->price * $item['count'];
            if($objItem->postage_type == Item::POSTAGE_TYPE_BUYER){
                $postageFlag ++;
            }


            if($objItem->sale_status==Item::SALE_STATUS_NO){
                return RestHelp::encodeResult(23006,sprintf("%s已停售",$objItem->title));
            }

            if ($objItem->shelf_status == Item::SHELF_STATUS_NO or !array_key_exists($objItem->id, $shelfItems)) {
                return RestHelp::encodeResult(23006,sprintf("%s已下架",$objItem->title));
            }

            //库存
            if($objItem->stock < $item['count']){
                return RestHelp::encodeResult(23006,sprintf("%s库存不足",$objItem->title));
            }
        }

        $postage = $postageFlag ? $this->settingService->getSettingByKey(Setting::KEY_ORDER_POSTAGE)->value : 0;
        $grandTotal = $itemTotal+$postage;

        $sessionTotal = WxUserKv::getValue(Buyer::find($this->buyerId)->wxUser->id,WxUserKv::BUYER_CHECK_PRICE);


        if( (string)$sessionTotal != (string)$grandTotal){
            return RestHelp::encodeResult(22002,"illegal operation");
        }



        $discount  = 0;
        $orderType = Order::PAYMENT_TYPE_WX;


        //如果传入了订单号
        if(isset($inputData['orderNumber'])){
            //update
            $objOrder = Order::where('order_number',$inputData['orderNumber'])->first();
            if( ! $objOrder){
                return RestHelp::encodeResult(22001,"the order not found");
            }
            //new
            try{
                $orderId = $this->orderService->update($objOrder,$itemTotal,$grandTotal,$discount,$grandTotal-$discount,$inputData['isAnonymous'] ? 1 : 0,$postage,$orderType,$deliverAddressId);

            }catch (\Exception $e){
                return RestHelp::encodeResult(23004,$e->getMessage());
            }

        }else{
            //new
            try{
                $orderId = $this->orderService->create($orderNumber,$shopId,$this->buyerId,$itemTotal,$grandTotal,$discount,$grandTotal-$discount,$postage,$orderType,$deliverAddressId,$inputData['isAnonymous'] ? 1 : 0,null,null);

            }catch (\Exception $e){
                return RestHelp::encodeResult(23004,$e->getMessage());
            }

            //减少库存
            $commissonTotal = 0;
            foreach($inputData['items'] as $item){
                $objItem = Item::find($item['id']);
                $objItem->stock -= $item['count'];
                $objItem->save();

                //计算挨个佣金
                $commissonTotal  += ($objItem->commission) * $item['count'];
            }


            //生成佣金表数据
            $commisson = OrderCommission::firstOrNew([
                'order_id'=>$orderId
            ]);
            $commisson->amount = $objShop->is_direct_sale == 'Y' ? 0 : $commissonTotal;
            $commisson->status = OrderCommission::STATUS_UNCONFIRMED;
            $commisson->save();

            //生成seller commisson
            $sellerCommission = SellerCommission::firstOrNew([
                'order_id'=>$orderId,
                'seller_id'=>$objShop->seller_id

            ]);
            $sellerCommission->amount = $objShop->is_direct_sale == 'Y' ? 0 : $commissonTotal;
            $sellerCommission->status = SellerCommission::STATUS_UNCONFIRMED;
            $sellerCommission->save();

        }


        $subject = '';//32
        $body = '';//128
        $itemIdList = \DB::table('order_items')->where('order_id',$orderId)->lists('item_id');

        foreach($inputData['items'] as $item){

            if(isset($inputData['orderNumber'])){
                $orderItem = OrderItem::where('order_id',$orderId)->where('item_id',$item['id'])->first();
                $quantity = $orderItem ? $orderItem->quantity : 0;
                $count = $item['count'];
                $balance = $count - $quantity;
                $objItem = Item::find($item['id']);
                $objItem->stock -= $balance;
                $objItem->save();
                OrderItem::where('order_id',$orderId)->where('item_id',$item['id'])->forceDelete();

                //如果支付失败，返回修改删除了某个商品，则恢复库存 Step 1  @author zhengqian
                foreach($itemIdList as $k=>$itid){
                    if($item['id'] == $itid){
                        unset($itemIdList[$k]);
                    }
                }
            }

            $orderItem = new OrderItem();
            $orderItem->order_id = $orderId;
            $orderItem->item_id = $item['id'];
            $objItem = Item::find($item['id']);
            $orderItem->name = $objItem->name;
            $subject .= $objItem->title."*".$objItem->spec."*".$objItem->$item['count'].",";
            $body .= $objItem->title.$objItem->spec.$objItem->$item['count'];
            $orderItem->title = $objItem->title;
            $orderItem->code = $objItem->code;
            $orderItem->barcode = $objItem->barcode;
            $orderItem->type = ItemType::find($objItem->type_id)->name;
            $orderItem->quantity = $item['count'];
            $orderItem->price = $objItem->price;
            $orderItem->item_total = $objItem->price * $item['count'];
            $commissionsRate = $this->settingService->getSettingByKey(Setting::KEY_COMMISSIONS_RATE);

            if( ! $commissionsRate){
                $commissionsRate = Setting::DEFAULT_KEY_COMMISSIONS_RATE;
            }else{
                $commissionsRate = $commissionsRate->value;
            }

            $orderItem->commission = $orderItem->item_total * $commissionsRate;
            $orderItem->save();
        }

        //如果支付失败，返回修改删除了某个商品，则恢复库存 Step 2 @author zhengqian
        if(isset($inputData['orderNumber'])){

            foreach($itemIdList as $itid){
                $orderItem = OrderItem::where('order_id',$orderId)->where('item_id',$itid)->first();
                $quantity = $orderItem->quantity;
                $objItem = Item::find($itid);
                $objItem->stock += $quantity;
                $objItem->save();
                OrderItem::where('order_id',$orderId)->where('item_id',$itid)->forceDelete();
            }

        }

        $payment = new Payment();
        $payment->serial_number = OrderHelper::getPaymentSerialNumber(1);
        $payment->payment_number = '';
        $payment->order_id = $orderId;
        $payment->order_number = Order::find($orderId)->order_number;
        $payment->buyer_id = $this->buyerId;
        $payment->amount = $itemTotal;
        $payment->channel = Payment::PAYMENT_CHANNEL_PXX;
        $payment->type = Payment::PAYMENT_TYPE_WX;
        $payment->status = Order::PAY_STATUS_NO;
        $payment->save();




        try{

            \Pingpp\Pingpp::setApiKey('sk_live_3dKEivmziedjzitFhaHL7gYF');
            $ch = \Pingpp\Charge::create(
                array(
                    'order_no'  => $payment->serial_number,
                    'app'       => array('id' => 'app_XTOW5SXTWLGCGKef'),
                    'channel'   => 'wx_pub',
                    'amount'    => $sessionTotal*100,
                    'client_ip' => $this->inputData->ip(),
                    'currency'  => 'cny',
                    'subject'   => mb_substr($subject,0,32),
                    'body'      => mb_substr($body,0,128),
                    'extra'=>array('open_id' =>$wxUser->openid)
                )
            );
        }catch (\Exception $e){
            return RestHelp::encodeResult(22003,$e->getMessage(),['orderNum'=>$orderNumber]);
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
            'orderNumber'=>$orderNumber,
            'paymentNumber'=>$payment->serial_number,
            'charge'=>json_decode($ch,true)
        ]);

    }


    /**
     * 核实订单,库存
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function check()
    {
        $inputData = $this->inputData->only('items','shopShortId');

        $items = $inputData['items'];
        if( ! is_array($items)){
            return RestHelp::encodeResult(22000,'item must be array');
        }

        $arrRet = array();
        $postageFlag = 0;
        $totalPrice  = 0;
        $objShop = Shop::getShopByShort($inputData['shopShortId']);
        if( ! $objShop){
            return RestHelp::encodeResult(21000,"shop not found");
        }

        $shelfItems = $objShop->getItemsOnShelf();


        $resultFlag = true;
        $warning = '';
        foreach($items as $k=>$item){
            $arrRet[$k]['result'] = true;
            $arrRet[$k]['id'] = $item['id'];
            $objItem = Item::find($item['id']);


            if($objItem->sale_status==Item::SALE_STATUS_NO){
                $arrRet[$k]['result'] = false;
                $resultFlag = false;
                $warning = sprintf("%s 已停售",$objItem->name);
                break;
            }

            if ($objItem->shelf_status == Item::SHELF_STATUS_NO or !array_key_exists($objItem->id, $shelfItems)) {
                $arrRet[$k]['result'] = false;
                $resultFlag = false;
                $warning = sprintf("%s 已下架",$objItem->name);
                break;
            }


            if($objItem->stock < $item['count']){
                $arrRet[$k]['result'] = false;
                $resultFlag = false;
                $warning = sprintf("%s 库存不够",$objItem->name);
                break;
            }


            if($objItem->postage_type == Item::POSTAGE_TYPE_BUYER){
                $postageFlag ++;
            }

            $totalPrice += ($objItem->price)*$item['count'];

        }

        $postage = $postageFlag ? $this->settingService->getSettingByKey(Setting::KEY_ORDER_POSTAGE)->value : 0;
        $grandTotal = $totalPrice + $postage;

        //检查合法后将价格，总价放入DB,后期用于检查
        if($resultFlag){
            $user = Buyer::find($this->buyerId)->wxUser;
            WxUserKv::setValue($user->id,WxUserKv::BUYER_CHECK_PRICE,$grandTotal);
        }

        return RestHelp::success([
            'result'=>$resultFlag,
            'postage'=>$postage,
            'grandTotal'=>$grandTotal,
            'warning'=>$warning,
            'detail'=>$arrRet
        ]);
    }


}