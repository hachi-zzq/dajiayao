<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class Order extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'order';

    const STATUS_TO_PAY     = 10; // 待支付
    const STATUS_TO_DELIVER = 20; // 待发货
    const STATUS_TO_RECEIVE = 30; // 待收货
    const STATUS_REJECT     = 31; // 已拒收
    const STATUS_TO_REFUND  = 32; // 等待退款
    const STATUS_FINISH     = 40; // 已完成
    const STATUS_CLOSED     = 50; // 已关闭
    const STATUS_REFUND     = 60; // 已退款

    //zhengqian.zhu
    const STATUS_IN_RECYCLED = 70; //放入回收站中的

    // 发货状态
    const DELIVER_STATUS_YES = 1; // 已发货
    const DELIVER_STATUS_NO  = 0; // 未发货

    // 运送方式
    const DELIVER_METHOD_EXPRESS = 1; // 普通快递
    const DELIVER_METHOD_FAST    = 2; // 1小时快送

    // 支付状态
    const PAY_STATUS_YES = 1; // 已支付
    const PAY_STATUS_NO  = 0; // 未支付

    // 支付类型
    const PAYMENT_TYPE_WX   = 'wx'; // 微信支付
    const PAYMENT_TYPE_ALIPAY  = 'alipay'; // 支付宝

    // 订单类型
    const ORDER_TYPE_NORMAL = 1; // 普通
    const ORDER_TYPE_FAST   = 2; // 快送

    // 匿名类型
    const ANONYMOUS_YES = 1; // 匿名
    const ANONYMOUS_NO  = 0; // 不匿名

    public function orderItems()
    {
        return $this->hasMany('Dajiayao\Model\OrderItem', 'order_id', 'id');
    }

    public static function getTodayCount(){
        $datePrefix = date('y-m-d', time());
        return self::where('created_at', '>=', $datePrefix . " 00:00:00")->where('created_at', '<=', $datePrefix . " 23:59:59")->count();
    }

    public function shop() {
        return $this->belongsTo('Dajiayao\Model\Shop', 'shop_id', 'id');
    }


    public function address()
    {
        return $this->hasOne('Dajiayao\Model\BuyerAddress','id','receiver_address_id');
    }

    public function commission() {
        return $this->hasOne('Dajiayao\Model\OrderCommission', 'order_id', 'id');
    }

    public function getPaymentTypeText() {
        $type = $this->payment_type;
        if ($type == self::PAYMENT_TYPE_WX) {
            return '微信支付';
        } elseif ($type == self::PAYMENT_TYPE_ALIPAY) {
            return '支付宝';
        } else {
            return '其他';
        }
    }

    public function buyer()
    {
        return $this->hasOne("Dajiayao\Model\Buyer",'id','buyer_id');
    }

    /**
     * 获取当前订单的状态 label
     * @author Hanxiang
     * @return string
     */
    public function getStatusLabel() {
        switch ($this->status) {
            case self::STATUS_TO_PAY:
                return '<span class="label label-warning">待支付</span>';
            case self::STATUS_TO_DELIVER:
                return '<span class="label label-info">待发货</span>';
            case self::STATUS_TO_RECEIVE:
                return '<span class="label label-primary">待收货</span>';
            case self::STATUS_REJECT:
                return '<span class="label label-danger">已拒收</span>';
            case self::STATUS_TO_REFUND:
                return '<span class="label label-danger">等待退款</span>';
            case self::STATUS_FINISH:
                return '<span class="label label-success">已完成</span>';
            case self::STATUS_CLOSED:
                return '<span class="label label-success">已关闭</span>';
            case self::STATUS_REFUND:
                return '<span class="label label-success">已退款</span>';
            default:
                return '';
        }
    }

    public function express()
    {
        return $this->hasOne('Dajiayao\Model\Express','id','express_id');
    }


    /**
     * 获取当前订单可用的操作
     * @author Hanxiang
     * @return string
     * @TODO
     */
    public function getAvailableOp() {
        if (in_array($this->status, [self::STATUS_TO_DELIVER])) {
            return sprintf('<a href="%s">%s</a>', route('adminOrdersDeliver', $this->order_number), "发货");
        } elseif (in_array($this->status, [self::STATUS_TO_PAY])) {
            return sprintf('<a href="%s" onclick="%s">%s</a>', route('adminOrderCancel', $this->order_number),  "return confirm('确认要取消该订单吗')", "取消");
        } else {
            return '';
        }
    }

    /**
     * 获取当前订单类型名称
     * @author Hanxiang
     * @return string
     */
    public function getOrderTypeLabel() {
        if ($this->order_type == self::ORDER_TYPE_NORMAL) {
            return '<span class="label label-primary">普通订单</span>';
        } elseif ($this->order_type == self::ORDER_TYPE_FAST) {
            return '<span class="label label-success">快送订单</span>';
        } else {
            return '';
        }
    }

}
