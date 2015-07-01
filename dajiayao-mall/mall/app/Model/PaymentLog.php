<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class PaymentLog extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'payment_log';

}
