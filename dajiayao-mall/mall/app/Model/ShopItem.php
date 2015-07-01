<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class ShopItem extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'shop_items';

    const STATUS_YES = 1;
    const STATUS_NO = 0;


    public function item()
    {
        return $this->hasOne('Dajiayao\Model\Item', 'id', 'item_id');
    }

    /**
     * 获取某id商品的上架店铺数量
     * @author Hanxiang
     * @param $item_id
     * @return int
     */
    public static function getShopCountByItemID($item_id) {
        return self::where('item_id', $item_id)->count();
    }


    public static function getByShop($shopId){
        return self::where('shop_id',$shopId)->orderBy('sort')->get();
    }

    public static function checkShopItemExist($shopId,$itemId){
        return self::where('shop_id', $shopId)->where('item_id', $itemId)->count()>0;
    }

    public static function getById($id){
        return self::where('id',$id)->first();
    }
}
