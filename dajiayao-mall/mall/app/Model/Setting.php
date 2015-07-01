<?php namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Hanxiang
 */
class Setting extends Model
{

    protected $table = 'settings';

    public $timestamps = false;

    /**
     * 佣金比例
     */
    const KEY_COMMISSIONS_RATE = 'commissions:rate';

    const DEFAULT_KEY_COMMISSIONS_RATE = 0.1;

    /**
     * 订单最晚在N小时内支付
     */
    const KEY_ORDER_PAYMENT_DURATION = 'order:payment:duration';

    /**
     * 订单最晚在N小时内自动收货
     */
    const KEY_ORDER_AUTO_RECEIVE_DURATION = 'order:auto:receive:duration';

    /**
     * 统一邮费
     */
    const KEY_ORDER_POSTAGE = 'order:postage';

    const DEFAULT_KEY_ORDER_POSTAGE = 10;




    public static function getByKey($key)
    {
        return self::where('key', $key)->first();
    }

    public static function getByKeys(array $keys)
    {
        return self::whereIn('key', $keys)->get();
    }

    public static function getAll()
    {
        return self::all();
    }

}
