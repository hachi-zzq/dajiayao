<?php

/*
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/6/5
 * Time: 14:50
 */

namespace Dajiayao\Library\Device;

/**
 *
 * Class DeviceClient
 * @package Dajiayao\Library\Weixin
 */
class DeviceClient
{
    const API_GET_ACCESS_TOKEN = 'http://device.yayao.mobi/rest/v1/token?appid=%s&t=%s&sign=%s';
    const API_GET_DEVICE = 'http://device.yayao.mobi/rest/v1/weixin/device?token=%s';
    const API_GET_DEVICE_BY_SN = 'http://device.yayao.mobi/rest/v1/weixin/device/%s?token=%s';
    const API_UPDATE_DEVICE_COMMENT = 'http://device.yayao.mobi/rest/v1/weixin/device/%s/update_comment?token=%s';
    const API_UPDATE_DEVICE_LOCATION = 'http://device.yayao.mobi/rest/v1/weixin/device/%s/location?token=%s';
    const API_CREATE_PAGE = 'http://device.yayao.mobi/rest/v1/weixin/page/create?token=%s';
    const API_UPDATE_PAGE = 'http://device.yayao.mobi/rest/v1/weixin/page/%s/update?token=%s';
    const API_DELETE_PAGE = 'http://device.yayao.mobi/rest/v1/weixin/page/delete?token=%s';
    const API_BIND_PAGE = 'http://device.yayao.mobi/rest/v1/weixin/device/%s/bind_page?token=%s';
    const API_BIND_DEVICE = 'http://device.yayao.mobi/rest/v1/weixin/page/%s/bind_device?token=%s';
    const API_GET_PAGE = 'http://device.yayao.mobi/rest/v1/weixin/page/%s?token=%s';
    const API_GET_SHAKE_INFO = 'http://device.yayao.mobi/rest/v1/weixin/shakaround/shakeinfo?token=%s&ticket=%s';

    const ACCESS_TOKEN_EXPIRES_IN = 7200;

    /**
     * @param $data_string
     * @param $url
     * @param $options
     * @return mixed
     */
    protected function doPost($url, $data_string, $options = [])
    {
        $headers = array('Content-Type' => 'application/json');
        $options['timeout'] = 600;
        $response = \Requests::post($url, $headers, $data_string, $options);
        return json_decode($response->body);
    }

    /**
     * 申请access_token
     * @param $appid
     * @param $secret
     * @return mixed
     * @throws \Exception
     */
    public function applyAccessToken($appid, $secret)
    {
        $t = time();
        $md5Sign = md5($appid . $secret . $t);

        $url = sprintf(self::API_GET_ACCESS_TOKEN, $appid, $t, $md5Sign);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data->access_token;
        } else {
            throw new \Exception("Device get access_token error");
        }
    }

    /**
     * 获得设备列表
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function getDevices($token)
    {
        $url = sprintf(self::API_GET_DEVICE, $token);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data;
        } else {
            throw new \Exception("Device get devices error");
        }
    }

    /**
     * 获得设备列表
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function getDeviceBySN($sn, $token)
    {
        $url = sprintf(self::API_GET_DEVICE_BY_SN, $sn, $token);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data;
        } else {
            throw new \Exception("Device get device by SN error");
        }
    }

    /**
     * 更新设备备注
     * @param $sn
     * @param $comment
     * @param $token
     * @return bool
     * @throws \Exception
     */
    public function updateDeviceComment($sn, $comment, $token)
    {
        $url = sprintf(self::API_UPDATE_DEVICE_COMMENT, $sn, $token);
        $data = array('comment' => $comment);
        $rtJson = $this->doPost($url, json_encode($data));
        if ($rtJson->msgcode == 10000) {
            return true;
        } else {
            throw new \Exception("Device update device comment error");
        }
    }

    /**
     * 设置设备位置
     * @param $sn
     * @param array $location （{“longitude”:2314.455,"latitude": 1334.134,"address": "江苏省苏州市晋合广场","position": "2号楼1143房间东南墙角"}）
     * @param $token
     * @return bool
     * @throws \Exception
     */
    public function updateDeviceLocation($sn, array $location, $token)
    {
        $url = sprintf(self::API_UPDATE_DEVICE_LOCATION, $sn, $token);
        $rtJson = $this->doPost($url, json_encode($location));
        if ($rtJson->msgcode == 10000) {
            return true;
        } else {
            throw new \Exception("Device update device location error");
        }
    }


    /**
     * 新增页面
     * @param array $page ({"title":"周边主标题","description":"周边副标题","icon_url":"http://domain.com/pics/d9342e.png","url":"http://click.domain.com/beaconshow.html","comment":"comment" //备注"}     )
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function createPage(array $page, $token)
    {
        $url = sprintf(self::API_CREATE_PAGE, $token);
        $rtJson = $this->doPost($url, json_encode($page));
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data->page_id;
        } else {
            throw new \Exception("Device create page error");
        }
    }

    /**
     * 更新页面
     * @param $pageId
     * @param array $page ({"title":"周边主标题","description":"周边副标题","icon_url":"http://domain.com/pics/d9342e.png","url":"http://click.domain.com/beaconshow.html","comment":"comment" //备注"}     )
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function updatePage($pageId, array $page, $token)
    {
        $url = sprintf(self::API_UPDATE_PAGE, $pageId, $token);
        $rtJson = $this->doPost($url, json_encode($page));
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data->page_id;
        } else {
            throw new \Exception("Device update page error");
        }
    }

    /**
     * 页面绑定设备
     * @param $pageId
     * @param array $snArray
     * @param bool $isBind
     * @param bool $isAppend
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function bindDevice($pageId, array $snArray, $isBind = true, $isAppend = false, $token)
    {
        $data = array("sn" => $snArray, 'bind' => $isBind ? 1 : 0, 'append' => $isAppend ? 1 : 0);
        $url = sprintf(self::API_BIND_DEVICE, $pageId, $token);
        $rtJson = $this->doPost($url, json_encode($data));
        dd($rtJson);
        if ($rtJson->msgcode == 10000) {
            return true;
        } else {
            throw new \Exception("Device page bind device  error");
        }
    }


    /**
     * 设备绑定页面
     * @param $deviceId
     * @param array $pagesArray
     * @param bool $isBind
     * @param bool $isAppend
     * @param $token
     * @return bool
     * @throws \Exception
     */
    public function bindPage($deviceId, array $pagesArray, $isBind = true, $isAppend = false, $token)
    {
        $data = array("page_ids" => $pagesArray, 'bind' => $isBind ? 1 : 0, 'append' => $isAppend ? 1 : 0);
        $url = sprintf(self::API_BIND_PAGE, $deviceId, $token);
        $rtJson = $this->doPost($url, json_encode($data));
        if ($rtJson->msgcode == 10000) {
            return true;
        } else {
            throw new \Exception("Device page bind device  error");
        }
    }


    /**
     * 获得页面
     * @param $pageId
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function getPage($pageId, $token)
    {
        $url = sprintf(self::API_GET_PAGE, $pageId, $token);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data;
        } else {
            throw new \Exception("Device get page error");
        }
    }

    /**
     * 获得摇一摇用户信息
     * @param $ticket
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public function getShakeInfo($ticket, $token)
    {
        $url = sprintf(self::API_GET_SHAKE_INFO, $ticket, $token);
        $response = \Requests::get($url);
        $rtJson = $response->body;
        $rtJson = json_decode($rtJson);
        if ($rtJson->msgcode == 10000) {
            return $rtJson->data;
        } else {
            throw new \Exception("Device get shake info error");
        }
    }

}