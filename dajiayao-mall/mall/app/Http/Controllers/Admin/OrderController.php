<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Auth;
use Dajiayao\Model\Address;
use Dajiayao\Model\Buyer;
use Dajiayao\Model\BuyerAddress;
use Dajiayao\Model\Express;
use Dajiayao\Model\Order;
use Dajiayao\Model\OrderItem;
use Dajiayao\Model\Shop;
use Dajiayao\Model\Item;
use Dajiayao\Model\ShopAddress;
use Dajiayao\Services\BuyerService;
use Dajiayao\Services\OrderService;
use Dajiayao\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrderController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Hanxiang
 */
class OrderController extends Controller {

    /**
     * 订单列表
     * @author Hanxiang
     * TODO
     */
    public function index() {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return; //TODO
        }

        $orders = Order::where('id', '>', 0);
        $input = Input::all();
        if (isset($input['status']) && $input['status'] > 0) {
            $orders = $orders->where('status', $input['status']);
            $status = $input['status'];
        } else {
            $status = -1;
        }

        $orders = $orders->orderBy('updated_at', 'desc')->paginate(20);

        foreach ($orders as $order) {
            $shop = $order->shop;
            $seller = Shop::find($shop->id)->seller;
            $wxUser = $seller->wxUser;
            $order->shopObj = $shop;
            $order->seller = $seller;
            $order->sellerWxUser = $wxUser;
            $order->buyer = Buyer::find($order->buyer_id);
            $order->buyerWx = $order->buyer->wxUser;

            // address
            $address = BuyerAddress::find($order->receiver_address_id)->addresses;
            $order->address = $address->getFather();
        }

        return view('admin.orders.index')
            ->with('status', $status)
            ->with('orders', $orders);
    }

    /**
     * 发货
     * @author Hanxiang
     * @param $num
     * @return view
     */
    public function deliver($num) {
        $order = Order::where('order_number', $num)->first();
        if (!count($order)) {
            abort(404);
        }

        if ($order->status != Order::STATUS_TO_DELIVER) {
            return redirect()
                ->route('adminOrders')
                ->with('error_tips', "该订单当前不能做发货操作");
        }

        $expresses = Express::where('status', 1)->orderBy('sort')->get();

        return view('admin.orders.deliver')
            ->with('order', $order)
            ->with('expresses', $expresses);
    }

    /**
     * 发货POST
     * @author Hanxiang
     * @param OrderService $orderService
     * @param BuyerService $buyerService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deliverPost(OrderService $orderService, BuyerService $buyerService) {
        $input = Input::all();
        $validator = Validator::make($input, [
            'order_number' => 'required',
            'express_id' => 'required',
            'express_num' => 'required'
        ]);
        if($validator->fails()){
            return redirect()
                ->back()
                ->with('error_tips', $validator->messages()->first());
        }

        $rt = $orderService->deliver($input['order_number'], $input['express_id'], $input['express_num']);
        if ($rt['r']) {
            $order = Order::where('order_number', $input['order_number'])->first();
            $buyerService->sendDeliveredMsg($order);
            return redirect()
                ->route('adminOrders')
                ->with('success_tips', $rt['msg']);
        } else {
            return redirect()
                ->back()
                ->with('error_tips', $rt['msg']);
        }
    }

    /**
     * 发货ajax
     * @author Hanxiang
     * @param OrderService $orderService
     * @param BuyerService $buyerService
     * @return \Illuminate\Http\Response
     */
    public function deliverAjax(OrderService $orderService, BuyerService $buyerService) {
        $input = Input::all();
        $validator = Validator::make($input, [
            'order_number' => 'required',
            'express_id' => 'required',
            'express_num' => 'required'
        ]);
        if($validator->fails()){
            Session::flash('error_tips', $validator->messages()->first());
            return response()->json(['result' => 0]);
        }

        $rt = $orderService->deliver($input['order_number'], $input['express_id'], $input['express_num']);
        if ($rt['r']) {
            // TODO 发送消息，通知买家
            $buyerService->sendDeliveredMsg(Order::where('order_number', $input['order_number'])->first(), '');
            Session::flash('success_tips', $rt['msg']);
            return response()->json(['result' => 1]);
        } else {
            Session::flash('error_tips', $rt['msg']);
            return response()->json(['result' => 0]);
        }
    }

    /**
     * 订单详情
     * @author Hanxiang
     * @param $number 订单号
     * @return view
     */
    public function detail($number) {
        $order = Order::where('order_number', $number)->first();
        if (count($order) == 0) {
            abort(404);
        }

        // Buyer Info
        $buyer = Buyer::find($order->buyer_id);
        if (count($buyer) == 0) {
            $buyer = new \stdClass(); //TODO
            $wxUser = new \stdClass(); //TODO
        } else {
            $wxUser = $buyer->wxUser;
        }

        $total = 0;
        $quantity = 0;
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $oi) {
            $total += $oi->item_total;
            $quantity += $oi->quantity;
            $supplier = Item::find($oi->item_id)->supplier;
            $oi->supplier = $supplier;
        }
        $order->totalPrice = $total;
        $order->totalQuantity = $quantity;

        $expresses = Express::where('status', 1)->orderBy('sort')->get();

        $express = Express::find($order->express_id);
        if (count($express) == 0) {
            $express = new \stdClass();
            $express->name = '';
        }
        $order->expressObj = $express;

        $commission = $order->commission;
        if (!$commission) {
            $commission = new \stdClass();
            $commission->amount = 0;
            $order->commission = $commission;
        }

        return view('admin.orders.detail')
            ->with('order', $order)
            ->with('buyer', $buyer)
            ->with('wxUser', $wxUser)
            ->with('orderItems', $orderItems)
            ->with('expresses', $expresses);
    }

    /**
     * 修改订单
     * @author Hanxiang
     * TODO
     */
    public function updateAjax() {
        $input = Input::all();
        $validator = Validator::make($input, [
            'order_number' => 'required'
        ]);
        if($validator->fails()){
            Session::flash('error_tips', "参数错误");
            return response()->json(['result' => 0]);
        }

        Order::where('order_number', $input['order_number'])
            ->update(['comment' => $input['comment']]);
        Session::flash('success_tips', "操作成功");
        return response()->json(['result' => 1]);
    }

    /**
     * @author Hanxiang
     * @param OrderService $orderService
     * @param $num
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(OrderService $orderService, $num) {
        $order = Order::where('order_number', $num)->first();
        if (count($order) == 0) {
            abort(404);
        }

        if ($order->status != Order::STATUS_TO_PAY) {
            return redirect()->route('adminOrderDetail', $num)->with('error_tips', "当前不能取消");
        }

        $orderService->cancel($order->order_number);
        return redirect()->route('adminOrders', $num)->with('success_tips', "操作成功");
    }

}
