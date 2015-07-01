<?php namespace Dajiayao\Services;

use Dajiayao\Library\Mq\MQ;
use Dajiayao\Library\Weixin\WeixinClient;
use Dajiayao\Model\Buyer;
use Dajiayao\Model\Seller;
use Illuminate\Support\Facades\Config;
use Validator;
use Auth;
use J20\Uuid\Uuid;

/**
 * 卖家操作 Service
 * @author Haiming
 */
class SellerService
{

    private $_newOrderData = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'topcolor' => '#FF0000',
        'data' => [
            'first' => [
                'value' => '店铺新订单成交通知',
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
            'keyword4' => [
                'value' => '',
                'color' => '#173177'
            ],
            'remark' => [
                'value' => '该张订单完成后，店铺提成可以在我的佣金页申请提现。',
                'color' => '#173177'
            ]
        ]
    ];


    public function getAllSellers($page = -1)
    {
        if ($page == -1) {
            return Seller::getAll();
        } else {
            return Seller::getAllWithPage($page);
        }
    }

    /**
     * 新订单通知
     * @author Hanxiang
     * @param Order $order
     */
    public function sendNewOrderMsg($order)
    {
        //TODO
    }


    /**
     * 付款成功通知
     * @param $order
     * @return bool
     */
    public function sendPaidMsg($order)
    {
        $seller = $order->shop->seller;
        if(!$seller->wxUser){
            return false;
        }
        $wxUser = Buyer::find($order->buyer_id)->wxUser;
        $this->_newOrderData['touser'] = $seller->wxUser->openId;
        $this->_newOrderData['template_id'] = Config::get('weixin.template_id.new_order_to_seller');
        $this->_newOrderData['url'] = 'http://www.yayao.mobi';//TODO
        $this->_newOrderData['data']['keyword1']['value'] = '¥' . $order->amount_tendered;

        // get order_items
        $itemsStr = '';
        $orderItems = $order->orderItems;
        foreach ($orderItems as $oi) {
            $itemsStr .= ($oi->name . ' x' . $oi->quantity . ' ');
        }

        $this->_newOrderData['data']['keyword2']['value'] = $itemsStr; // 商品名称
        $this->_newOrderData['data']['keyword3']['value'] = $order->order_number; // 订单号
        $this->_newOrderData['data']['keyword4']['value'] = $wxUser->nickname;

        $commission = 0;
        $commissionService = new CommissionService();
        $sellerCommission = $commissionService->getSellerCommissionByOrder($seller->id, $order->id);
        if ($sellerCommission) {
            $commission = $sellerCommission->amount;
        }
        $this->_newOrderData['data']['remark']['value'] = "佣金：¥" . $commission.'。该张订单完成后，店铺提成可以在我的佣金页申请提现。';

        $this->_sendTplMsg($this->_newOrderData);
        return true;
    }

    public function getSellerById($id)
    {
        return Seller::getById($id);
    }

    /**
     * 根据微信的 media_id 下载文件
     * @author Hanxiang
     * @param $media_id
     * @param string $type
     * @return bool|string
     */
    public function downloadMedia($media_id, $type = '')
    {
        $mq = new MQ();
        $access_token = $mq->getWeixinAccessTokenByName('test'); // TODO

        $url = WeixinClient::API_GET_MEDIA;
        $url = sprintf($url, $access_token, $media_id);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $obj = json_decode($res, true);
        curl_close($ch);
        if (isset($obj['errcode']) && $obj['errcode'] == 40007) {
            return false;
        }

        $fp = fopen($url, 'r');
        $filePath = "shopimages/" . Uuid::v4(false) . ".jpg";
        file_put_contents(public_path($filePath), $fp);
        return $filePath;
    }


    private function _sendTplMsg($data)
    {
        $mq = new MQ();
        $access_token = $mq->getWeixinAccessTokenByName('seller');

        $wxClient = new WeixinClient();
        $wxClient->sendTemplateMessage(json_encode($data), $access_token);
    }
}
