<?php namespace Dajiayao\Services;

use Dajiayao\Model\Express;

/**
 * 快递公司操作 Service
 * @author Haiming
 */
class ExpressService
{
    public function getAllExpresses()
    {
        return Express::orderBy('sort')->get();
    }

    public function getExpressById($id)
    {
        return Express::where('id',$id)->first();
    }


}
