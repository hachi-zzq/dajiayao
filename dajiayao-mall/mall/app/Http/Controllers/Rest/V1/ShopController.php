<?php namespace Dajiayao\Http\Controllers\Rest\V1;
use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Util\ImageUtil;
use Dajiayao\Model\FavoriteShop;
use Dajiayao\Model\Image;
use Dajiayao\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


/**
 * Class BaseController
 * @package Dajiayao\Http\Controllers
 */

class ShopController extends BaseController
{

    protected $redis;

    public function __construct(Request $request)
    {
        $this->redis = Redis::connection();
        parent::__construct($request);
    }


    public function test()
    {
        header("content-type:image");
        $url = public_path('upload/product.jpg');
        $url = ImageUtil::getRuleImgSize($url,100,100);
        echo file_get_contents($url);
    }


    /**
     * 收藏与取消收藏
     * @param $id
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function favorite()
    {
        $inputdata = $this->inputData->only('shopShortId','flag');

        $validator = Validator::make($inputdata,[
            'shopShortId'=>'required',
            'flag'=>'required|boolean'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $objShop = Shop::getShopByShort($inputdata['shopShortId']);

        if( ! $objShop){
            return RestHelp::encodeResult(21001,"shop not found in db");
        }

        $buyerId = $this->buyerId;
        if($inputdata['flag'] == false){
            FavoriteShop::where('buyer_id',$buyerId)->where('shop_id',$objShop->id)->delete();
        }elseif($inputdata['flag'] == true){
            $favorite = new FavoriteShop();
            $favorite->buyer_id = $buyerId;
            $favorite->shop_id = $objShop->id;
            $favorite->save();
        }else{
            return RestHelp::encodeResult(21001,'flag is only 0 or 1');
        }

        return RestHelp::success();
    }


    /**
     * 店铺详情
     * @param $id
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function detail($short_id)
    {

        if( ! $short_id){
            return RestHelp::parametersIllegal("shop id is requird");
        }


        $objShop = Shop::where('short_id',$short_id)->first();
        if( ! $objShop){
            return RestHelp::encodeResult(21000,"shop is not found in db");
        }

        $buyerId = $this->buyerId;
        $favShop = FavoriteShop::where("shop_id",$objShop->id)->where("buyer_id",$buyerId)->first();
        $favorite = $favShop ? 1 : 0;

        $seller = $objShop->seller;
        $arrSeller = array();
        $arrSeller['id'] = $seller->id;
        $arrSeller['name'] = $seller->wxUser?$seller->wxUser->nickname:$seller->realname;
        $arrSeller['mobile'] = $seller->mobile;

        $banner = "";
        $arrShop = array();
        $arrShop['shortId'] = $objShop->short_id;
        $arrShop['name'] = $objShop->subtitle;
        $arrShop['banner'] = $banner;
        $arrShop['ad'] = "/1.png";
        $arrShop['avatar'] = $objShop->thumbnail;
        $arrShop['type'] = $objShop->type;
        $arrShop['banner'] = $objShop->banner ? ImageUtil::getRuleImgSize($objShop->banner,750,246) :'';

        $arrShop['region']['provinceId'] = $objShop->province_id;
        $arrShop['region']['cityId'] = $objShop->city_id;
        $arrShop['region']['countyId'] = $objShop->county_id;


        $items = array_values($objShop->getItemsOnShelf());
        $arrItem = array();
        foreach ($items as $k=>$item) {
            $arrItem[$k]['id'] = $item->id;
            $arrItem[$k]['title'] = $item->title;
            $arrItem[$k]['name'] = $item->name;
            $objImage = $item->image->first();
            $imagUrl = $objImage ? ImageUtil::getRuleImgSize($objImage->url,260,260) : "";
            $arrItem[$k]['image'] = $imagUrl;
            $arrItem[$k]['code'] = $item->code;
            $arrItem[$k]['supplier'] = $item->supplier->title;
            $arrItem[$k]['spec'] = $item->spec;
            $arrItem[$k]['weight'] = $item->weight;
            $arrItem[$k]['volume'] = $item->volume;
            $arrItem[$k]['price'] = $item->price;
            $arrItem[$k]['marketPrice'] = $item->market_price;

            //总计售出
            $sellsCount = $this->redis->get("dajiayao:mall:item:sellcount:".$item->id);
            $arrItem[$k]['sales'] = $sellsCount ? (int)$sellsCount : 0;
            $arrItem[$k]['comment'] = $item->comment;

            //以前购买人数
            $arrRedisBuyers = $this->redis->smembers("dajiayao:mall:item:buyers:".$item->id);
            $arrBuyers = array();
            foreach($arrRedisBuyers as $b=>$buyers){
                if($b<4) //最多五个
                    array_push($arrBuyers,json_decode($buyers));
            }
            $arrItem[$k]['buyers'] = $arrBuyers;

        }

        //TODO 支付方式
        $arrPayment = [
            'alipay'
        ];

        //TODO 广告位，推广
        $arrPromotions = [
            [
                "title"=>"星巴克",
                "link"=>"#",
                "image"=>ImageUtil::getRuleImgSize("/shopimages/starbucks_promotion.jpg",1176,210)
            ]

        ];


        return RestHelp::success([
            'favorite'=>$favorite,
            'shop'=>$arrShop,
            'promotions'=>$arrPromotions,
            'owner'=>$arrSeller,
            "availablePayments"=>$arrPayment,
            'items'=>$arrItem,
            'visitorCount'=>rand(100000,105000)
        ]);


    }




}