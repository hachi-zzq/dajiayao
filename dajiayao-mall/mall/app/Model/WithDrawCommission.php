<?php namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithDrawCommission extends Model {

    use SoftDeletes;

    const STATUS_DRAWEDING= 10; //申请中

    const STATUS_DRAWED= 20; //申请成功

    const STATUS_FAIL = 30; //申请驳回

    protected $dates = ['deleted_at'];

    protected $table = 'withdraw_commissions';


    public function seller()
    {
        return $this->hasOne('Dajiayao\Model\Seller','id','seller_id');
    }

    /**
     * 获取当前订单的状态 label
     * @author Hanxiang
     * @return string
     */
    public function getStatusLabel() {
        switch ($this->status) {
            case self::STATUS_DRAWEDING:
                return '<span class="label label-info">待处理</span>';
            case self::STATUS_DRAWED:
                return '<span class="label label-success">提现成功</span>';
            case self::STATUS_FAIL:
                return '<span class="label label-danger">提现失败</span>';
            default:
                return '';
        }
    }
}
