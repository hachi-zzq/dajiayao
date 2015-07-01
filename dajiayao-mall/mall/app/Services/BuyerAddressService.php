<?php namespace Dajiayao\Services;
use Dajiayao\Model\BuyerAddress;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/10
 */

class BuyerAddressService
{

    public function index($buyerId=null)
    {
        if(is_null($buyerId)){
            return BuyerAddress::all();
        }else{
            return BuyerAddress::where('buyer_id',$buyerId)->get();
        }
    }


    public function create($buyerId,$countyId,$address,$postcode,$receiver,$mobile,$default=0)
    {
        $deliverAddress = new BuyerAddress();
        $deliverAddress->buyer_id = $buyerId;
        $deliverAddress->address_id = $countyId;
        $deliverAddress->address = $address;
        $deliverAddress->postcode = $postcode;
        $deliverAddress->receiver = $receiver;
        $deliverAddress->mobile = $mobile;
        $deliverAddress->default = $default;
        $deliverAddress->save();

        return $deliverAddress->id;
    }


    public function destroy(BuyerAddress $buyerAddress)
    {
        return $buyerAddress->delete();
    }


    public function update(BuyerAddress $deliverAddress,$buyerId,$countyId,$address,$postcode,$receiver,$mobile,$default=0)
    {
        $deliverAddress->buyer_id = $buyerId;
        $deliverAddress->address_id = $countyId;
        $deliverAddress->address = $address;
        $deliverAddress->postcode = $postcode;
        $deliverAddress->receiver = $receiver;
        $deliverAddress->mobile = $mobile;
        $deliverAddress->default = $default;
        $deliverAddress->save();
        return true;
    }



}