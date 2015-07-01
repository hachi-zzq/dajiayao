<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeixinDevice extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_devices';

    public function mp()
    {
        return $this->hasOne('Dajiayao\Model\WeixinMp', 'id', 'wx_mp_id');
    }

    public function page()
    {
        return $this->hasOne('Dajiayao\Model\WeixinPage', 'id', 'wx_page_id');
    }

    public function weixinDevice()
    {
        return $this->hasMany('Dajiayao\Model\Device','wx_device_id','id');
    }

    /**
     * 获得配置的页面数
     * @return mixed
     */
    public function getPageCount(){
        return DevicePage::where('wx_device_id',$this->id)->count();
    }
}
