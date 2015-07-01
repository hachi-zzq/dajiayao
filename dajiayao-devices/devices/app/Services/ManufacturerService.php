<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/5/13
 * Time: 13:59
 */

namespace Dajiayao\Services;

use Dajiayao\Model\Manufacturer;


class ManufacturerService
{
    public function getManufacturers()
    {
        return Manufacturer::all();
    }

    public function getManufacturerById($id)
    {
        return Manufacturer::where('id', $id)->first();
    }

    public function isExist($id)
    {
        return $this->getManufacturerById($id) != null;
    }

}