<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class Buyer extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'buyers';

    public function addresses()
    {
        return $this->hasMany('Dajiayao\Model\BuyerAddress', 'buyer_id', 'id');
    }

    public function favoriteShops()
    {
        return $this->hasMany('Dajiayao\Model\FavoriteShop', 'buyer_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('Dajiayao\Model\Order', 'buyer_id', 'id');
    }

    public function wxUser()
    {
        return $this->hasOne('Dajiayao\Model\WxUser', 'id', 'wx_user_id');
    }


    public function save(array $option=array())
    {
        if( ! $obj = self::where('wx_user_id',$this->wx_user_id)->first()){
            return parent::save($option);
        }else{
            return $obj;
        }
    }
}
