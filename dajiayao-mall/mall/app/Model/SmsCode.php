<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class SmsCode extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'sms_code';

    /**
     * 检查手机号和验证码是否已存在
     * @author Hanxiang
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function checkMobile($mobile, $code) {
        $smsCode = self::where('mobile', $mobile)->first();
        if (count($smsCode) > 0) {
            if ((time() - strtotime($smsCode->created_at)) < 30 ) { // TODO
                return false; //存在，且小于30秒
            } else {
                $smsCode->code = $code;
                $smsCode->save();
                return true;
            }
        } else {
            $smsCode = new SmsCode();
            $smsCode->mobile = $mobile;
            $smsCode->code = $code;
            $smsCode->ip = '';
            $smsCode->save();
            return true;
        }
    }

    /**
     * 检查验证码
     * @author Hanxiang
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function checkCode($mobile, $code) {
        $smsCode = self::where('mobile', $mobile)->where('code', $code)->first();
        if (count($smsCode) > 0) {
            $smsCode->delete();
            return true;
        } else {
            return false;
        }
    }
}
