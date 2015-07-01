<?php namespace Dajiayao\Services;

use Dajiayao\Model\PaymentType;

/**
 * 支付类型操作 Service
 * @author Haiming
 */
class PaymentTypeService
{
    public function getAllPaymentTypes()
    {
        return PaymentType::orderBy('sort')->get();
    }

    public function getPaymentTypeById($id)
    {
        return PaymentType::where('id',$id)->first();
    }

    public function getPaymentTypeByType($type)
    {
        return PaymentType::where('type',$type)->first();
    }
}
