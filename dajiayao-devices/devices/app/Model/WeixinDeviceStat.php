<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeixinDeviceStat extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_device_statistics';

    public function mp()
    {
        return $this->belongsTo('Dajiayao\Model\WeixinMp', 'mp_id');
    }

}
