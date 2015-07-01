<?php namespace Dajiayao\Services;

use Dajiayao\Model\WxUser;
use Dajiayao\User;
use Validator;
use Auth;

/**
 * 微信用户操作 Service
 * @author Hanxiang
 */
class WxUserService {

    public function getWxUserByOpenId($openId){
        return WxUser::getByOpenId($openId);
    }
}
