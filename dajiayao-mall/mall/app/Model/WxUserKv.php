<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class WxUserKv extends Model {


    protected $table = 'wx_user_kv';


    const BUYER_CHECK_PRICE = 'buyer-check-price';


    public $timestamps = false;


    public static function getValue($wxUserId,$key)
    {
        $obj = self::where('wx_user_id',$wxUserId)->where('key',$key)->first();
        if( ! $obj){
            return null;
        }else{
            return $obj->value;
        }
    }



    public static function setValue($wxUserId,$key,$value)
    {
        $obj = self::where('wx_user_id',$wxUserId)->where('key',$key)->first();
        if( ! $obj){
            $obj = new WxUserKv();
            $obj->wx_user_id = $wxUserId;
            $obj->key = $key;
        }

        $obj->value = $value;

        $obj->save();
        return $obj;

    }


}
