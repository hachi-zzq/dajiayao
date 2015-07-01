<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class Payment extends Model {

    const PAYMENT_STATUS_SUCCESS = 1;

    const PAYMENT_STATUS_FAIL = 0;

    const PAYMENT_STATUS_CANCEL = -1;


    const PAYMENT_CHANNEL_PXX = 'P++';

    const PAYMENT_TYPE_WX = 'wx';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'payments';

    public static function getTodayCount(){
        $datePrefix = date('y-m-d', time());
        return self::where('created_at', '>=', $datePrefix . " 00:00:00")->where('created_at', '<=', $datePrefix . " 23:59:59")->count();
    }

    public function order()
    {
        return $this->hasOne("Dajiayao\Model\Order",'id',"order_id");
    }

}
