<?php namespace Dajiayao\Services;

use Dajiayao\Library\Device\DeviceClient;
use Dajiayao\Library\Mq\MQ;
use Dajiayao\Model\Seller;
use Dajiayao\Model\SellerCommission;
use Dajiayao\Model\Shop;
use Dajiayao\Model\ShopAddress;
use Dajiayao\Model\ShopDevice;
use Config;
use Dajiayao\Model\ShopItem;

/**
 * 佣金操作 Service
 * @author Haiming
 */
class CommissionService
{

    function getSellerCommissionBySeller($sellerId = null, $page = -1)
    {
        $query = SellerCommission::where('amount', '>', 0)->with('order')->with('seller');
        if ($sellerId) {
            $query = $query->where('seller_id', $sellerId)->orderBy('created_at', 'desc');
        }
        if ($page == -1) {
            return $query->get();
        } else {
            return $query->paginate($page);
        }
    }

    function getSellerCommissionByOrder($sellerId, $orderId)
    {
        $query = SellerCommission::where('order_id', $orderId)->where('seller_id', $sellerId);
        return $query->first();
    }

}
