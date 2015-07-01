<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WxUser extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_users';

    public function mp()
    {
        return $this->belongsTo('Dajiayao\Model\WeixinMp', 'wx_mp_id');
    }

}
