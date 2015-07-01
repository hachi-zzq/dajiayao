<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'apps';

    protected $guarded = ['id'];


    const TYPE_WEIXIN = 1;
    const TYPE_OTHER = 0;

    const STATUS_NORMAL = 0;
    const STATUS_LOCKED = -1;


    public function user()
    {
        return $this->belongsTo('Dajiayao\User');
    }

    public function wxMp()
    {
        return $this->hasMany("Dajiayao\Model\WeixinMp",'app_id');
    }

    public function typeName()
    {
        if ($this->type == self::TYPE_WEIXIN) {
            return '微信';
        }
        return "其他";
    }

    public function statusName()
    {

        if ($this->status == self::STATUS_LOCKED) {
            return '冻结';
        }
        return '正常';
    }

    public function getMp()
    {
        if ($this->type == self::TYPE_WEIXIN) {
            return  WeixinMp::where('app_id',$this->id)->first();
        }
        return null;
    }
}
