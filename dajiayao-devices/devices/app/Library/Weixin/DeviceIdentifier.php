<?php
/**
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/5/6
 * Time: 9:26
 */

namespace Dajiayao\Library\Weixin;


class DeviceIdentifier
{
    public $device_id;
    public $uuid;
    public $major;
    public $minor;

    function __construct($device_id, $uuid, $major, $minor)
    {
        $this->device_id = (int)$device_id;
        $this->uuid = $uuid;
        $this->major = (int)$major;
        $this->minor = (int)$minor;
    }

}