<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class WxUser extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'wx_users';

    const ROLE_BUYER = 1;
    const ROLE_SELLER = 2;

    public static function getByOpenId($openId){
        return self::where('openid',$openId)->first();
    }


    /**
     * @保存用户信息
     * @param，openid返回的用户json
     * @author zhengqian,zhu
     */
    public static function saveWxUser($userJson)
    {
        if( ! $userJson){
            throw new \Exception("user json is required");
        }
        $obj = json_decode($userJson);
        $user = self::where("openid",$obj->openid)->first();
        if( ! $user){
            $user = new self;
        }
        $user->nickname = isset($obj->nickname) ? $obj->nickname :'';
        $user->openid = $obj->openid;
        $user->sex = isset($obj->sex) ? $obj->sex : 0;
        $user->province = isset($obj->province) ? $obj->province : '';
        $user->city = isset($obj->city) ? $obj->city : '';
        $user->country = isset($obj->country) ? $obj->country : '';
        $user->headimgurl = isset($obj->headimgurl) ? $obj->headimgurl : '';
        $user->subscribe = $obj->subscribe;
        $user->unionid = isset($obj->unionid) ? $obj->unionid : null;
        $user->save();
        return $user->id;
    }

}
