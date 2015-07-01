<?php namespace Dajiayao\Services;

use Dajiayao\Library\Device\DeviceClient;
use Dajiayao\Library\Mq\MQ;
use Dajiayao\Model\Seller;
use Dajiayao\Model\Shop;
use Dajiayao\Model\ShopAddress;
use Dajiayao\Model\ShopDevice;
use Config;
use Dajiayao\Model\ShopItem;

/**
 * 店铺操作 Service
 * @author Haiming
 */
class ShopService
{

    private $mq;

    function __construct()
    {
        $this->mq = new MQ();
        $this->deviceClient = new DeviceClient();
    }

    function getAllShop($page=-1)
    {
        if($page==-1){
            return Shop::getAll();
        }else{
            return Shop::getAllWithPage($page);
        }
    }

    /**
     * 开店
     * @param Seller $seller
     * @param $type
     * @param $mode
     * @param $title
     * @param $subtitle
     * @param $comment
     * @param $iconUrl
     * @throws \Exception
     */
    public function createShop(Seller $seller, $type, $mode, $title, $subtitle, $comment, $iconUrl,$bannerPath)
    {
        $shop = new Shop();
        $shop->name = $subtitle;
        $shop->title = $title;
        $shop->subtitle = $subtitle;
        $shop->comment = $comment;
        $shop->thumbnail = $iconUrl;
        $shop->banner = $bannerPath;
        $shop->type = $type;
        $shop->mode = $mode;
        $shop->seller_id = $seller->id;

        $shop->province_id = 310000;
        $shop->city_id = 310100;
        $shop->county_id = 310110;

        $shortUrl = $this->getShortId();
        $shop->short_id = $shortUrl;
        $shop->save();
        $shop_id = $shop->id;

        // TODO
        $shopAddr = new ShopAddress();
        $shopAddr->address_id = 320000;
        $shopAddr->detail = "华池街88号1143室";
        $shopAddr->shop_id = $shop_id;
        $shopAddr->save();
    }

    /**
     * @param Shop $shop
     * @param $sn
     * @return bool|mixed
     * @throws \Exception
     */
    public function bindDevice(Shop $shop, $sn)
    {
        $shopDevice = ShopDevice::getBySn($sn);
        if ($shopDevice->shop_id) {
            return false;
        }
        $shopDevice->shop_id = $shop->id;
        $shopDevice->device_sn = $sn;
        $token = $this->mq->getDeviceAccessToken();
        $rt = $this->deviceClient->bindDevice($shop->page_id, array($sn,), true, true, $token);
        if ($rt) {
            $rt = $shopDevice->save();
            $shop->status = Shop::STATUS_ACTIVE;

            $url = Config::get('app.shop_base_url') . $shop->short_id;
            $token = $this->mq->getDeviceAccessToken();
            $page = array(
                'title' => $shop->title,
                'description' => $shop->subtitle,
                'icon_url' => Config::get('app.domain.oc').$shop->thumbnail,
                'url' => $url,
                'comment' => $shop->comment
            );
            $pageId = $this->deviceClient->createPage($page, $token);
            $shop->url = $url;
            $shop->page_id = $pageId;
            $shop->save();
        }
        return $rt;
    }

    /**
     * 获取4位店铺短网址
     * 生成一个 $l 位的36进制随机字符串，其第一位字符不为数字
     * @author Hanxiang
     * @param $l
     * @return string
     */
    public function getShortId($l = 4)
    {
        $c = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        for ($s = '', $cl = strlen($c) - 1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i) ;
        $s[0] = is_numeric($s[0]) ? $letters[mt_rand(0, strlen($letters) - 1)] : $s[0];

        $shop = Shop::where('short_id', $s)->first();
        if (count($shop) > 0) {
            return $this->getShortId();
        }
        return $s;
    }

    public function getShopById($id)
    {
        return Shop::getById($id);
    }

    public function updateShopPage($shop)
    {
        $page = array('title' => $shop->title, 'description' => $shop->subtitle, 'comment' => $shop->comment);
        $token = $this->mq->getDeviceAccessToken();
        $this->deviceClient->updatePage($shop->page_id, $page, $token);
    }

    public function getShopItems($shopId)
    {
        return ShopItem::getByShop($shopId);
    }

    public function checkShopItemExist($shopId,$itemId)
    {
        return ShopItem::checkShopItemExist($shopId, $itemId);
    }

    public function getShopItem($shopItemId)
    {
        return ShopItem::getById($shopItemId);
    }
}
