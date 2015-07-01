<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceModel extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'device_models';

    public function manufacturer()
    {
        return $this->belongsTo('Dajiayao\Model\Manufacturer', 'manufacturer_id');
    }

    public function getShortDate() {
        return date('m/d/Y', strtotime($this->created_at));
    }

    public function getBatteryExpireDate() {
        $battery_lifetime = $this->battery_lifetime;
        return date('m/d/Y', strtotime($this->created_at) + $battery_lifetime * 2952000); // 30 * 24 * 60 *60
    }

}
