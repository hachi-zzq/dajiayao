<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed open_status
 * @property mixed type
 * @property mixed status
 * @author Hanxiang
 */
class Shop extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'shops';

    const TYPE_FIXED    = 1; // 一小时快送
    const TYPE_DIRECT   = 2; // 直销

    const MODE_NORMAL = 1; // 普通
    const MODE_SINGLE = 2; // 单品

    const OPEN_STATUS_OPEN   = 1; // 营业
    const OPEN_STATUS_CLOSE  = 0; // 关闭


    const STATUS_ACTIVE   = 1; // 激活
    const STATUS_INACTIVE  = 0; // 未激活

    // 是否官方直营店
    const IS_DIRECT_SALE_YES = 'Y'; // 是
    const IS_DIRECT_SALE_NO  = 'N'; // 否

    public function items()
    {
        return $this->belongsToMany('Dajiayao\Model\Item','shop_items');
    }

    public function shopItems()
    {
        return $this->hasMany('Dajiayao\Model\ShopItem','shop_id', 'id');
    }

    public function seller() {
        return $this->belongsTo('Dajiayao\Model\Seller', 'seller_id', 'id');
    }


    public static function getShopByShort($shortId)
    {
        return self::where('short_id', $shortId)->first();
    }


    public static function getAll(){
        return self::orderBy('is_direct_sale','desc')->orderBy('created_at','desc')->get();
    }

    public static function getAllWithPage($page){
        return self::orderBy('is_direct_sale','desc')->orderBy('created_at','desc')->paginate($page);
    }


    public function getOpenStatusName(){
        if($this->open_status==self::OPEN_STATUS_OPEN){
            return "营业";
        }
        return "关闭";
    }

    public function getStatusName(){
        if($this->status==self::STATUS_ACTIVE){
            return "激活";
        }
        return "未激活";
    }

    public function getTypeName(){
        if($this->type==self::TYPE_DIRECT){
            return "直销";
        }
        if($this->type==self::TYPE_FIXED){
            return "固定点";
        }
        return "";
    }

    public static function getById($id)
    {
        return self::where('id', $id)->first();
    }


    public function getAvailableItems()
    {
        $shopItem = ShopItem::where('shop_id',$this->id)->where('status',1)->with('item')->get();
        $arr = array();
        foreach($shopItem as $st){
            array_push($arr,$st->item);
        }

        return $arr;
    }

    /**
     * 获取货架上商品
     * @return array
     */
    public function getItemsOnShelf()
    {
        $arr = array();
        $items = $this->getAvailableItems();
        foreach($items as $item){
            if($item->shelf_status==Item::SHELF_STATUS_YES) {
                $arr[$item->id]=$item;
            }
        }
        return $arr;
    }


}
