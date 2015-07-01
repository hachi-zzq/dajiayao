<?php namespace Dajiayao\Http\Controllers\Buyer;


use Dajiayao\Model\FavoriteShop;
use Dajiayao\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{

    protected $arrStatus = [
        'no_paid'=>Order::STATUS_TO_PAY,
        'no_send'=>Order::STATUS_TO_DELIVER,
        'no_received'=>Order::STATUS_TO_RECEIVE,
        'finished'=>Order::STATUS_FINISH,
        'payback_confirm'=>Order::STATUS_TO_REFUND,
        'payback_complete'=>Order::STATUS_REFUND,
        'closed'=>Order::STATUS_CLOSED,
        'delete'=>Order::STATUS_IN_RECYCLED
    ];


    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->jsFileHash = [
            'vendor_js'=>hash_file('md2',public_path('assets/scripts/vendor.js')),
            'orders_js'=>hash_file('md2',public_path('assets/scripts/customer/orders.js')),
            'shop_css'=>hash_file('md2',public_path('assets/stylesheets/shop.css')),
            'app_css'=>hash_file('md2',public_path('assets/stylesheets/app.css')),
            'customer_css'=>hash_file('md2',public_path('assets/stylesheets/customer/customer.css')),
            'orders_css'=>hash_file('md2',public_path('assets/stylesheets/customer/orders.css')),
        ];
    }

    /**
     * 订单列表
     * @author zhengqian@dajiayao.cc
     */
    public function index()
    {
        $orders = Order::where('buyer_id',$this->buyerId)->where('status','!=',70);


        $input = $this->inputData->only('status');

        $validator = Validator::make($input,[
            'status'=>""
        ]);


        $arrStatus = [];
        if( (! $input['status']) or (array_key_exists('status',$input) and $input['status'] == 'all')){
            $arrStatus = [Order::STATUS_TO_PAY,Order::STATUS_TO_DELIVER,Order::STATUS_TO_RECEIVE,Order::STATUS_FINISH,Order::STATUS_CLOSED,Order::STATUS_REFUND];
        }

        if(array_key_exists('status',$input) && $input['status'] == 'no_paid'){
            $arrStatus = [Order::STATUS_TO_PAY];
        }

        if(array_key_exists('status',$input) && $input['status'] == 'no_send'){
            $arrStatus = [Order::STATUS_TO_DELIVER];
        }

        if(array_key_exists('status',$input) && $input['status'] == 'no_received'){
            $arrStatus = [Order::STATUS_TO_RECEIVE];

        }

        $orders = $orders->whereIn('status',$arrStatus)->orderBy('created_at','DESC')->get();

        foreach ($orders as $order) {
            $order->statusClass = '';
            if($order->status == Order::STATUS_TO_PAY){
                $order->statusClass = 'paying';
            }
            if($order->status == Order::STATUS_TO_RECEIVE){
                $order->statusClass = 'shipping';
            }
            if($order->status == Order::STATUS_FINISH){
                $order->statusClass = 'finished';
            }
            if($order->status == Order::STATUS_TO_DELIVER){
                $order->statusClass = 'packaging';
            }
            if($order->status == Order::STATUS_CLOSED){
                $order->statusClass = 'closed';
            }

        }




        return view('buyer.order.index')->with('orders',$orders)->with('input',$input)->with('hash_file',$this->jsFileHash);

    }


    /**
     * 店铺状态修改
     * @param $orderNumber
     * @param $status，当前的状态码，而不是改变之后的状态码
     * @author zhengqian@dajiayao.cc
     */
    public function setStatus($orderNumber,$status)
    {
        $order = Order::where('order_number',$orderNumber)->first();
        if( ! $order){

            echo "订单不存在";
            exit;
        }

        switch ($status){
            case 'delete':
                //删除订单
                $order->status = $this->arrStatus['delete'];
                $status = 'all';
                break;
            case 'cancel':
                //取消订单
                $order->status = $this->arrStatus['closed'];
                $status = 'no_paid';
                //回复库存
                $this->recoverStock($order);
                break;
            case 'pay_continue':
                //继续支付

                break;
            case 'payback':
                //退款
                $order->status = $this->arrStatus['closed'];
                $status = 'no_send';
                break;

            case 'received':
                //确认收货
                $order->status = $this->arrStatus['finished'];
                $status = 'no_received';

                break;
            default:
                echo "非法操作";
                exit;
        }

        $order->save();

        return sprintf("<script>alert('%s');window.location.href='%s'</script>",'操作成功','/buyer/orders/list?status='.$status);

    }

    /**
     * 手动关闭，撤销库存的修改
     * @param Order $order
     * @author zhengqian@dajiayao.cc
     */
    public function recoverStock(Order $order)
    {
        //恢复库存
        $orderItems = $order->orderItems;
        foreach ($orderItems as $orderItem) {
            $item = $orderItem->items;
            $item->stock += $orderItem->quantity;
            $item->save();
        }

        return true;
    }


    /**
     * 订单详情
     * @param $orderNumber
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function detail($orderNumber)
    {

        $order = Order::where('order_number',$orderNumber)->first();
        if( ! $order){

            echo "订单不存在";
            exit;
        }

        $order->statusClass = '';
        if($order->status == Order::STATUS_TO_PAY){
            $order->statusClass = 'paying';
        }
        if($order->status == Order::STATUS_TO_RECEIVE){
            $order->statusClass = 'shipping';
        }
        if($order->status == Order::STATUS_FINISH){
            $order->statusClass = 'finished';
        }
        if($order->status == Order::STATUS_TO_DELIVER){
            $order->statusClass = 'packaging';
        }
        if($order->status == Order::STATUS_CLOSED){
            $order->statusClass = 'closed';
        }

        $county = $order->address->addresses;
        $city = $county->getFather();
        $province = $city->getFather();
        $address = $province->address.$city->address.$county->address.$order->address->address;
        return view('buyer.order.detail')->with('order',$order)->with('hash_file',$this->jsFileHash)->with('address',$address);


    }

    /**
     * 我购买过的
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function myBuyed()
    {
        $orders = Order::where('buyer_id',$this->buyerId)->get();

        $arrShop = [];

        foreach ($orders as $order) {
            $shop = $order->shop;

            if( ! array_key_exists($shop->id,$arrShop)){
                $arrShop[$shop->id] = $order;
                $arrShop[$shop->id]['favorite'] = FavoriteShop::where('buyer_id',$this->buyerId)->where('shop_id',$shop->id)->first();
            }
        }

        return view('buyer.shop.mybuyed')->with('orders',$arrShop);
    }


    /**
     * 我浏览过的
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function myBrowse()
    {
        $orders = Order::where('buyer_id',$this->buyerId)->get();

        $arrShop = [];

        foreach ($orders as $order) {
            $shop = $order->shop;

            if( ! array_key_exists($shop->id,$arrShop)){
                $arrShop[$shop->id] = $order;
                $arrShop[$shop->id]['favorite'] = FavoriteShop::where('buyer_id',$this->buyerId)->where('shop_id',$shop->id)->first();
            }
        }

        return view('buyer.shop.mybrowse')->with('orders',$arrShop);
    }



}