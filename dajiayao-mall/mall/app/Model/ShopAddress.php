<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class ShopAddress extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'shop_addresses';

}
