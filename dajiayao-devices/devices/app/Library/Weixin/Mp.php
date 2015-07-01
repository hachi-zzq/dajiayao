<?php
/**
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/5/6
 * Time: 9:38
 */

namespace Dajiayao\Library\Weixin;


class Mp {

    public $name;
    public $mp_id;
    public $appsecret;
    public $appid;
    public $comment;
    public $app_id;

    function __construct($name,$mp_id, $appid, $appsecret, $app_id,$comment='')
    {
        $this->name = $name;
        $this->mp_id = $mp_id;
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->comment = $comment;
        $this->app_id = $app_id;
    }
}