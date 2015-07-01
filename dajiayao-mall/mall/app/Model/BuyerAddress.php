<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class BuyerAddress extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'buyer_addresses';


    public function addresses()
    {
        return $this->hasOne('Dajiayao\Model\Address','id','address_id');
    }



}
