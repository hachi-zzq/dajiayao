<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class OrderItem extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'order_items';

    public function order()
    {
        return $this->hasOne('Dajiayao\Model\Order', 'id', 'order_id');
    }


    public function items()
    {
        return $this->hasOne('Dajiayao\Model\Item','id','item_id');
    }

    /**
     * 获取某id商品的总销量
     * @author Hanxiang
     * @param $item_id
     * @return int
     */
    public static function getSaleCountByItemID($item_id) {
        $orderItems = self::where('item_id', $item_id)->get();
        $count = 0;
        foreach ($orderItems as $oi) {
            $count += $oi->quantity;
        }
        return $count;
    }

    /**
     * 获取某id商品今天的销量
     * @author Hanxiang
     * @param $item_id
     * @return int
     */
    public static function getTodaySaleCountByItemID($item_id) {
        $time = time();
        $orderItems = self::where('item_id', $item_id)
            ->where('created_at', '>', date('Y-m-d 00:00:00', $time))
            ->where('created_at', '<', date('Y-m-d 23:59:59', $time))
            ->get();
        $count = 0;
        foreach ($orderItems as $oi) {
            $count += $oi->quantity;
        }
        return $count;
    }


}
