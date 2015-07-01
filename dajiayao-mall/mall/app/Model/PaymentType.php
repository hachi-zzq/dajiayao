<?php namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Haiming
 */
class PaymentType extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'payment_types';

    const STATUS_CLOSE = -1;
    const STATUS_OPEN = 1;

}
