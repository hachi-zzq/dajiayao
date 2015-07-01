<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/5/13
 * Time: 13:59
 */

namespace Dajiayao\Services;

use Dajiayao\Model\UserKv;


class UserKvService
{
    const REDIRECT_URL_KEY = "REDIRECT_URL_KEY";

    /**
     * 获得重定向URL列表
     * @return array
     */
    public function getRedirectUrls()
    {
        $rt = array();
        $redirect = UserKv::where('key', self::REDIRECT_URL_KEY);
        if ($redirect) {
            $rt = UserKv::where('parent_id', $redirect->id);
        }
        return $rt;
    }

    public function getUserKvByParentKey($key)
    {
        $rt = array();
        $redirect = UserKv::where('key', $key);
        if ($redirect) {
            $rt = UserKv::where('parent_id', $redirect->id);
        }
        return $rt;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserKvById($id)
    {
        return UserKv::where('id', $id)->first();
    }
}