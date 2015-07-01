<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeixinPage extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_pages';


    public function mp()
    {
        return $this->belongsTo('Dajiayao\Model\WeixinMp', 'wx_mp_id');
    }

    public function device()
    {
        return $this->belongsToMany('Dajiayao\Model\WeixinDevice','device_page','wx_page_id','wx_device_id');
    }

    /**
     * 获取绑定的设备数量
     * @return mixed
     */
    public function getDeviceCount(){
        return DevicePage::where('wx_page_id',$this->id)->count();
    }
}
