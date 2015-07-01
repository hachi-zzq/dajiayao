<?php namespace Dajiayao\Http\Controllers\Rest\Buyer\V1;

use Dajiayao\Library\Help\RestHelp;

use Dajiayao\Model\FavoriteShop;
use Dajiayao\Model\Shop;

class ShopController extends BaseController
{

    /**
     * 收藏与取消收藏
     * @param $id
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function favorite()
    {
        $inputdata = $this->inputData->only('shopShortId','flag');


        $validator = \Validator::make($inputdata,[
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

        $buyerId = is_null($this->buyerId) ? 0 : $this->buyerId ;
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
}