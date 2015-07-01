<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;

class DeviceApp extends Model {

    protected $table = 'device_app';

    public function device()
    {
        return $this->hasOne('Dajiayao\Model\Device', 'id', 'device_id');
    }

    public function app()
    {
        return $this->hasOne('Dajiayao\Model\App', 'id', 'app_id');
    }
}
