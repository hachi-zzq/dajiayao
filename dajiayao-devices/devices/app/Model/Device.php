<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'devices';

    public function manufacturer()
    {
        return $this->belongsTo('Dajiayao\Model\Manufacturer');
    }

    public function model()
    {
        return $this->belongsTo('Dajiayao\Model\DeviceModel');
    }


    public function weixinDevice()
    {
        return $this->hasOne('Dajiayao\Model\WeixinDevice', 'id', 'wx_device_id');
    }

    public static $status = [
        0 => '待分配',
        1 => '待烧号',
        2 => '烧号完成'
    ];

    /**
     * 根据id列表获得SN列表，并且按照id列表顺序排序
     * @param array $ids
     * @return array
     */
    public static function findSNsByIds(array $ids)
    {
        $map = self::whereIn('id', $ids)->lists('sn', 'id');
        $snArray = array();
        foreach ($ids as $id) {
            array_push($snArray, $map[$id]);
        }
        return $snArray;
    }

    /**
     * @param $sn
     * @return mixed
     * @author zhengqian@dajiayao.cc
     */
    public static function getDeviceBySn($sn)
    {
        return self::where("sn",$sn)->first();
    }
}
