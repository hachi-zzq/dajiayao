<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class OrderCommission extends Model {

    protected $fillable = array('order_id', 'amount', 'status');

    use SoftDeletes;

    const STATUS_UNCONFIRMED = 10; //待确认

    const STATUS_CONFIRMED = 20; //订单成功，已经确认

    protected $dates = ['deleted_at'];

    protected $table = 'order_commissions';

}
