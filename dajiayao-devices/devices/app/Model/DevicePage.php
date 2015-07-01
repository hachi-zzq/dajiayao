<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;

class DevicePage extends Model {

    protected $table = 'device_page';

    public $timestamps = false;

    public function device()
    {
        return $this->hasOne('Dajiayao\Model\WeixinDevice', 'id', 'wx_device_id');
    }

    public function page()
    {
        return $this->hasOne('Dajiayao\Model\WeixinPage', 'id', 'wx_page_id');
    }

    public function checkExist()
    {
        return self::where('wx_device_id',$this->wx_device_id)->where('wx_page_id',$this->wx_page_id)->first();
    }


    public function save(array $option=array())
    {

        if( ! $this->checkExist()){
            return parent::save($option);
        }

        return true;

    }


}
