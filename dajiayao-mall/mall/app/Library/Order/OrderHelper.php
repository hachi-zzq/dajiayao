<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/6/8
 * Time: 16:11
 */

namespace Dajiayao\Library\Order;


use Carbon\Carbon;
use Dajiayao\Model\Order;
use Dajiayao\Model\Payment;
use Illuminate\Support\Facades\Cache;
use Skip32;

class OrderHelper
{
    const CACHE_KEY_ORDER_COUNT = "order:count:";
    const CACHE_KEY_PAYMENT_COUNT = "payment:count:";

    const ENCRYPTED_KEY = "115a82c2a81a635214cf";

    // 使用 Wincache 作为流水号计数器缓存
    public static function getOrderSerialNumber()
    {
        $timestamp = time();
        $datePrefix = date('ymd', $timestamp);
        $key = self::CACHE_KEY_ORDER_COUNT . $datePrefix;
        // 如果流水号计数器数据不在缓存中，则尝试从数据库中恢复
        if (!Cache::has($key)) {
            // 从数据库中获取今日的订单数
            $counter = Order::getTodayCount();
            $expiresAt = Carbon::now()->addMinutes(1440);
            Cache::put($key, $counter, $expiresAt);
        }
        $value = Cache::increment($key);
        return $datePrefix . str_pad($value, 10, '0', STR_PAD_LEFT);
    }

    /**
     *
     * 生成流水号算法：第1位为支付渠道，2-7位为日期，8-17位是当日流水经过Skip32加密过的流水号，可解密出真实流水。最后3位为随机数。
     * @param $prefix 1位数字，标记支付类型.1:微信支付,2:支付宝支付,3:银联支付
     * @return string
     */
    public static function getPaymentSerialNumber($prefix)
    {
        $timestamp = time();
        $datePrefix = date('ymd', $timestamp);
        $key = self::CACHE_KEY_PAYMENT_COUNT . $datePrefix;
        if (!Cache::has($key)) {
            $counter = Payment::getTodayCount();
            $expiresAt = Carbon::now()->addMinutes(1440);
            Cache::put($key, $counter, $expiresAt);
        }
        $value = Cache::increment($key);
        $value = str_pad(Skip32::encrypt(self::ENCRYPTED_KEY, $value), 10, '0', STR_PAD_LEFT);
        return $prefix . $datePrefix . $value . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    }

}

