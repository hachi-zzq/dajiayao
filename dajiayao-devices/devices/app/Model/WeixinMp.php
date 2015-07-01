<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeixinMp extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_mp';

    protected $guarded = ['id'];


    public function app()
    {
        return $this->belongsTo('Dajiayao\Model\App', 'app_id');
    }

    public static function  getByApp($appId){
        return self::where('app_id',$appId)->first();
    }
}
