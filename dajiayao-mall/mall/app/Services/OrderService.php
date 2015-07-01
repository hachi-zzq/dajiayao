<?php namespace Dajiayao\Services;
use Dajiayao\Model\BuyerAddress;
use Dajiayao\Model\Order;
use Dajiayao\Model\Express;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/11
 */

class OrderService
{

    public function create($orderNumber,
                           $shopId,
                           $buyerId,
                           $itemTotal,
                           $grandTotal,
                           $discount_total,
                           $amount_tendered,
                           $postage,
                           $orderType,
                           $receiverAddressId,
                           $isAnonymous,
                           $paymentSerialNumber,
                           $paymentId

    )
    {
        $order = new Order();
        $order->order_number = $orderNumber;
        $order->shop_id = $shopId;
        $order->buyer_id = $buyerId;
        $order->item_total = $itemTotal;
        $order->grand_total = $grandTotal;
        $order->discount_total = $discount_total;
        $order->amount_tendered = $amount_tendered;
        $order->postage = $postage;
        $order->order_type = $orderType;
        $order->receiver_address_id = $receiverAddressId;
        $buyerAddr = BuyerAddress::find($receiverAddressId);
        if( ! $buyerAddr){
            throw new \Exception(sprintf("buyer addr id : %s not found in db",$receiverAddressId));
        }
        $order->receiver_address = $buyerAddr->address;
        $address = $buyerAddr->addresses;
        $county = $address->address;
        $city = $address->getFather();
        $province = $city->getFather();
        $order->receiver_full_address = $province->address.$city->address.$county.$buyerAddr->address;
        $order->receiver = $buyerAddr->receiver;
        $order->receiver_phone = $buyerAddr->mobile;
        $order->receiver_postcode = $buyerAddr->postcode;
        $order->is_anonymous = $isAnonymous;
        $order->deliver_status = Order::DELIVER_STATUS_NO;
        $order->payment_serial_number = $paymentSerialNumber;
        $order->payment_id = $paymentId;
        $order->payment_type = Order::PAYMENT_TYPE_WX;;
        $order->payment_status = Order::PAY_STATUS_NO;;
        $order->status = Order::STATUS_TO_PAY;;

        $order->save();

        return $order->id;
    }

    /**
     * 订单发货操作
     * @author Hanxiang
     * @param $orderNumber
     * @param $express_id
     * @param $express_num
     * @return array
     */
    public function deliver($orderNumber, $express_id, $express_num) {

        $order = Order::where('order_number', $orderNumber)->first();
        if (!count($order)) {
            return ['r' => false, 'msg' => '订单不存在'];
        }

        $express = Express::find($express_id);
        if (!$express) {
            return ['r' => false, 'msg' => '快递参数错误'];
        }
        $order->express_id = $express_id;
        $order->express_number = $express_num;
        $order->deliver_status = Order::DELIVER_STATUS_YES;
        $order->status = Order::STATUS_TO_RECEIVE;
        $order->save();

        return ['r' => true, 'msg' => '操作成功'];
    }

    public function update(Order $order,
                           $itemTotal,
                           $grandTotal,
                           $discount_total,
                           $amount_tendered,
                           $isAnonymous,
                           $postage,
                           $orderType,
                           $receiverAddressId
                           )
    {

        $order->item_total = $itemTotal;
        $order->grand_total = $grandTotal;
        $order->discount_total = $discount_total;
        $order->amount_tendered = $amount_tendered;
        $order->postage = $postage;
        $order->order_type = $orderType;
        $order->receiver_address_id = $receiverAddressId;
        $buyerAddr = BuyerAddress::find($receiverAddressId);
        if( ! $buyerAddr){
            throw new \Exception(sprintf("buyer addr id : %s not found in db",$$receiverAddressId));
        }
        $order->receiver_address = $buyerAddr->address;
        $address = $buyerAddr->addresses;
        $county = $address->address;
        $city = $address->getFather();
        $province = $city->getFather();
        $order->receiver_full_address = $province->address.$city->address.$county.$buyerAddr->address;
        $order->receiver = $buyerAddr->receiver;
        $order->receiver_phone = $buyerAddr->mobile;
        $order->receiver_postcode = $buyerAddr->postcode;
        $order->is_anonymous = $isAnonymous;
        $order->deliver_status = Order::DELIVER_STATUS_NO;
        $order->payment_type = Order::PAYMENT_TYPE_WX;;
        $order->payment_status = Order::PAY_STATUS_NO;;
        $order->status = Order::STATUS_TO_PAY;;

        $order->save();

        return $order->id;
    }

    /**
     * 取消订单操作
     * @author Hanxiang
     * @param $orderNumber
     * @return bool
     */
    public function cancel($orderNumber) {
        $order = Order::where('order_number', $orderNumber)->first();
        if (count($order) == 0 || $order->status != Order::STATUS_TO_PAY) {
            return false;
        }
        $order->status = Order::STATUS_CLOSED;
        $order->save();
        $orderItems = $order->orderItems;
        foreach ($orderItems as $orderItem) {
            $item = $orderItem->items;
            $item->stock += $orderItem->quantity;
            $item->save();
        }
        return true;
    }

}
