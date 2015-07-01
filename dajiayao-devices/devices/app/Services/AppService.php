<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/5/13
 * Time: 13:59
 */

namespace Dajiayao\Services;

use \Dajiayao\Model\App;


class AppService
{
    public function getApps()
    {
        return App::all();
    }

    public function getAppsByUser($userId)
    {
        return App::where('user_id',$userId)->get();
    }

    public function getAppsByType($type)
    {
        return App::where('type', $type)->get();
    }

    public function getAppById($id)
    {
        return App::where('id', $id)->first();
    }

}