<?php namespace Dajiayao\Services;

use Dajiayao\Library\Device\DeviceClient;
use Dajiayao\Library\Mq\MQ;
use Dajiayao\Model\Item;
use Dajiayao\Model\Seller;
use Dajiayao\Model\Shop;
use Dajiayao\Model\ShopAddress;
use Dajiayao\Model\ShopDevice;
use Config;
use Dajiayao\Model\ShopItem;

/**
 * 商品操作 Service
 * @author Haiming
 */
class ItemService
{

    function getItemsByCode($code)
    {
        return Item::getByCode($code);
    }

}
