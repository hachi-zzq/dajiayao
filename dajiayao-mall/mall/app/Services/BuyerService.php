<?php namespace Dajiayao\Services;

use Dajiayao\Library\Mq\MQ;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\Buyer;
use Dajiayao\Model\Express;
use Dajiayao\Model\OrderItem;
use Illuminate\Support\Facades\Config;

class BuyerService {

    private $_deliverData = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'topcolor' => '#FF0000',
        'data' => [
            'first' => [
                'value' => '您的订单已发货',
                'color' => '#173177'
            ],
            'keyword1' => [
                'value' => '',
                'color' => '#173177'
            ],
            'keyword2' => [
                'value' => '',
                'color' => '#173177'
            ],
            'keyword3' => [
                'value' => '',
                'color' => '#173177'
            ],
            'remark' => [
                'value' => '点击可查看物流信息，欢迎再次购买！',
                'color' => '#173177'
            ]
        ]
    ];

    private $_newOrderData = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'topcolor' => '#FF0000',
        'data' => [
            'first' => [
                'value' => '您的订单已收到',
                'color' => '#173177'
            ],
            'keyword1' => [
                'value' => '',
                'color' => '#173177'
            ],
            'keyword2' => [
                'value' => '',
                'color' => '#173177'
            ],
            'keyword3' => [
                'value' => '',
                'color' => '#173177'
            ],
            'remark' => [
                'value' => '点击查看订单详情。',
                'color' => '#173177'
            ]
        ]
    ];

    private $_cancelOrderData = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'topcolor' => '#FF0000',
        'data' => [
            'first' => [
                'value' => '您的订单已取消',
                'color' => '#173177'
            ],
            'orderProductPrice' => [
                'value' => '',
                'color' => '#173177'
            ],
            'orderProductName' => [
                'value' => '',
                'color' => '#173177'
            ],
            'orderAddress' => [
                'value' => '',
                'color' => '#173177'
            ],
            'orderName' => [
                'value' => '',
                'color' => '#173177'
            ],
            'remark' => [
                'value' => '欢迎再次购买！',
                'color' => '#173177'
            ]
        ]
    ];

    public function __construct() {

    }

    /**
     * 发货提醒
     * @author Hanxiang
     * @param $order
     * @return bool
     */
    public function sendDeliveredMsg($order) {
        $wxUser = Buyer::find($order->buyer_id)->wxUser;
        $this->_deliverData['touser'] = $wxUser->openid;
        $this->_deliverData['template_id'] = Config::get('weixin.template_id.deliver');
        $this->_deliverData['url'] = sprintf(Config::get('app.kuaidi100'), $order->express_number);
        $this->_deliverData['data']['keyword1']['value'] = $order->order_number; // 订单号
        $this->_deliverData['data']['keyword2']['value'] = Express::find($order->express_id)->name; // 快递公司
        $this->_deliverData['data']['keyword3']['value'] = $order->express_number; // 物流单号

        $this->_sendTplMsg($this->_deliverData);
        return true;
    }

    /**
     * 订单生成，通知买家
     * @author Hanxiang
     * @param $order
     * @return bool
     * TODO
     */
    public function sendNewOrderMsg($order) {
        $wxUser = Buyer::find($order->buyer_id)->wxUser;
        $this->_newOrderData['touser'] = $wxUser->openid;
        $this->_newOrderData['template_id'] = Config::get('weixin.template_id.new_order');
        $this->_newOrderData['url'] = route('orderDetail',array('order_number'=>$order->order_number));
        $this->_newOrderData['data']['keyword1']['value'] = $order->created_at->format('Y年m月d日 H:i'); // 时间

        // get order_items
        $itemsStr = '';
        $orderItems = $order->orderItems;
        foreach ($orderItems as $oi) {
            $itemsStr .= ($oi->name . ' x' . $oi->quantity . ' ');
        }

        $this->_newOrderData['data']['keyword2']['value'] = $itemsStr; // 商品名称
        $this->_newOrderData['data']['keyword3']['value'] = $order->order_number; // 订单号
        $this->_newOrderData['data']['remark']['value']="下单成功，点击查看订单详情。";

        $this->_sendTplMsg($this->_newOrderData);
        return true;
    }

    /**
     * 取消订单，通知买家
     * @param $order
     * @return bool
     * TODO
     */
    public function sendCancelOrderMsg($order) {
        $wxUser = Buyer::find($order->buyer_id)->wxUser;
        $this->_cancelOrderData['touser'] = $wxUser->openid;
        $this->_cancelOrderData['template_id'] = Config::get('weixin.template_id.cancel_order');
        $this->_cancelOrderData['url'] = 'http://www.yayao.mobi'; //TODO
        $this->_cancelOrderData['data']['orderProductPrice'] = $order->amount_tendered; // 订单金额

        // get order_items
        $itemsStr = '';
        $orderItems = $order->orderItems;
        foreach ($orderItems as $oi) {
            $itemsStr .= ($oi->name . ' x' . $oi->quantity);
        }

        $this->_cancelOrderData['data']['orderProductName'] = $itemsStr; // 商品详情
        $this->_cancelOrderData['data']['orderAddress'] = $order->receiver_full_address; // 收货信息
        $this->_cancelOrderData['data']['orderName'] = $order->order_number; // 订单号

        $this->_sendTplMsg($this->_cancelOrderData);
        return true;
    }

    private function _sendTplMsg($data) {
        $mq = new MQ();
        $access_token = $mq->getWeixinAccessTokenByName('buyer');

        $wxClient = new WeixinClient();
        $wxClient->sendTemplateMessage(json_encode($data), $access_token);
    }
}