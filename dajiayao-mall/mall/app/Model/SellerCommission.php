<?php namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/26
 */
class SellerCommission extends Model
{
    protected $fillable = array('order_id', 'seller_id', 'amount', 'status');

    use SoftDeletes;

    const STATUS_UNCONFIRMED = 10; //待确认

    const STATUS_CONFIRMED = 20; //订单成功，已经确认

    protected $dates = ['deleted_at'];

    protected $table = 'seller_commissions';

    public function order()
    {
        return $this->hasOne('Dajiayao\Model\Order', 'id', 'order_id');
    }

    public function seller()
    {
        return $this->hasOne('Dajiayao\Model\Seller', 'id', 'seller_id');
    }
}