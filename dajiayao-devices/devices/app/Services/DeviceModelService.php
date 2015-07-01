<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/5/13
 * Time: 13:59
 */

namespace Dajiayao\Services;

use Dajiayao\Model\DeviceModel;

class DeviceModelService
{
    public function getDeviceModels()
    {

        return DeviceModel::all();
    }

    public function getDeviceModelById($id)
    {
        return DeviceModel::where('id', $id)->first();
    }


}