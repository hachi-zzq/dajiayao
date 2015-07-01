<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class Supplier extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'suppliers';

    public function items()
    {
        return $this->hasMany('Dajiayao\Model\Item', 'supplier_id', 'id');
    }

}
