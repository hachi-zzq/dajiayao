<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'manufacturers';

    /**
     * 获取所有厂商，附带型号
     * @author Hanxiang
     */
    public static function getAllWithModels() {
        $manufacturers = self::all();
        if (count($manufacturers) > 0) {
            foreach ($manufacturers as $man) {
                $deviceModels = DeviceModel::where('manufacturer_id',$man->id)->get();
                $man->models = $deviceModels;
            }
            return $manufacturers;
        } else {
            // TODO
            $m = new \stdClass();
            $m->id = '';
            $m->name = '';
            $model = new \stdClass();
            $model->id = '';
            $model->name = '';
            $model->manufacturer_sn = '';
            $model->created_at = '';
            $model->battery_lifetime = '';
            $model->default_password = '';
            $m->models = [0 => $model];
            return [0 => $m];
        }
    }
}
