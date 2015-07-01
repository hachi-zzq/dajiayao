<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeixinPageStat extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_page_statistics';

    public function mp()
    {
        return $this->belongsTo('Dajiayao\Model\WeixinMp', 'wx_mp_id');
    }

    public function page()
    {
        return $this->belongsTo('Dajiayao\Model\WeixinPage', 'wx_page_id');
    }
}
