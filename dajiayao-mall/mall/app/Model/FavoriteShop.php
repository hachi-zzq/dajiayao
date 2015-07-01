<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class FavoriteShop extends Model {


    protected $table = 'favorite_shops';

    public $timestamps = false;


    public function checkExist()
    {
        return self::where('buyer_id',$this->buyer_id)->where('shop_id',$this->shop_id)->first();
    }


    /**重写save方法
     * @param array $option
     * @author zhengqian@dajiayao.cc
     */
    public function save(array $option=array())
    {
        if( ! $this->checkExist()){
            return parent::save($option);
        }

        return true;
    }

    public function shop()
    {
        return $this->hasOne('Dajiayao\Model\Shop','id','shop_id');
    }

}
