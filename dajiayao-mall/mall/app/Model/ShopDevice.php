<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class ShopDevice extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'shop_devices';


    public static function getBySn($sn){
        return self::where('device_sn',$sn)->first();
    }

    public static function getAvailableSns(){
        return self::where('shop_id',0)->get();
    }

    public static function getOneAvailableSn(){
        return self::where('shop_id',0)->first();
    }

}
