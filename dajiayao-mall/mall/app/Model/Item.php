<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property  imgurl
 * @property mixed id
 * @author Hanxiang
 */
class Item extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'items';

    // 销售状态
    const SALE_STATUS_YES    = 1; // 在售
    const SALE_STATUS_NO   = 0; // 停售

    // 运费
    const POSTAGE_TYPE_BUYER  = 1; // 买家承担
    const POSTAGE_TYPE_SELLER = 2; // 卖家承担

    // 上架状态
    const SHELF_STATUS_YES = 1; // 允许
    const SHELF_STATUS_NO  = 0; // 不允许


    // 是否官方直营
    const IS_DIRECT_SALE_YES = 'Y'; // 是
    const IS_DIRECT_SALE_NO  = 'N'; // 否

    public function image()
    {
        return $this->belongsToMany('Dajiayao\Model\Image',
            'item_images',
            'item_id',
            'image_id');
    }

    public function supplier()
    {
        return $this->belongsTo('Dajiayao\Model\Supplier', 'supplier_id', 'id');
    }

    public function type()
    {
        return $this->hasOne('Dajiayao\Model\Type', 'id', 'type_id');
    }


    public static function getByCode($code)
    {
        return self::where('code',$code)->first();
    }

    public function getFirstImage()
    {
        $this->imgurl = asset('/themeforest/images/avatar.png');
        $itemImage = ItemImage::where('item_id', $this->id)->first();
        if (count($itemImage) > 0) {
            $image = $itemImage->image;
            if (count($image) > 0) {
                $this->imgurl = $image->url;
            }
        }
        return $this->imgurl;
    }

}
