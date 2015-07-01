<?php namespace Dajiayao\Http\Controllers\Rest\V1;
use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Model\Address;
use Dajiayao\Model\BuyerAddress;
use Dajiayao\Services\BuyerAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/4
 */

class AddressController extends BaseController
{

    protected $buyerAddressService;
    public function __construct(Request $request,BuyerAddressService $buyerAddressService)
    {
        $this->buyerAddressService = $buyerAddressService;

        parent::__construct($request);
    }
    /**
     * 收货地址列表
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function index()
    {

        $buyerId = $this->buyerId;
        $buyerAddress = BuyerAddress::where("buyer_id",$buyerId)->get();
        $arrRet = array();
        foreach ($buyerAddress as $k=>$addr) {
            $county = $addr->addresses;
            $city = $county->getFather();
            $province = $city->getFather();
            $arrRet[$k]['id'] = $addr->id;
            $arrRet[$k]['provinceId'] = $province->id;
            $arrRet[$k]['province'] = $province->address;
            $arrRet[$k]['cityId'] = $city->id;
            $arrRet[$k]['city'] = $city->address;
            $arrRet[$k]['countyId'] = $county->id;
            $arrRet[$k]['county'] = $county->address;
            $arrRet[$k]['address'] = $addr->address;
            $arrRet[$k]['postcode'] = $addr->postcode;
            $arrRet[$k]['receiver'] = $addr->receiver;
            $arrRet[$k]['mobile'] = $addr->mobile;
            $arrRet[$k]['default'] = $addr->default == 1 ? true : false;
        }

        return RestHelp::success(['addresses'=>$arrRet]);
    }


    /**新增收货地址
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function create()
    {
        $buyerId = $this->buyerId;
        $inputData = $this->inputData->all();
        $validator = Validator::make($inputData,[
           'countyId'=>'required',
           'address'=>'required',
           'postcode'=>'',
           'receiver'=>'required',
           'mobile'=>'required',
           'default'=>'required',

        ]);

        $countyId = $inputData['countyId'];

        if( ! $county = Address::find($countyId)){

            return RestHelp::encodeResult(23002,"地址不合法，请重新输入");
        }

        try{

            $city = $county->getFather();

            $province = $city->getFather();

        }catch (\Exception $e){

            return RestHelp::encodeResult(23002,"地址不合法，请重新输入");
        }



        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $newDeliverAddressid = $this->buyerAddressService->create($buyerId,$inputData['countyId'],$inputData['address'],isset($inputData['postcode']) ? $inputData['postcode'] : '',$inputData['receiver'],$inputData['mobile'],$inputData['default'] == true ? 1 : 0);

        if($inputData['default'] == true){
            BuyerAddress::whereRaw("id != $newDeliverAddressid and deleted_at is null")->where('buyer_id',$buyerId)->update([
                'default'=>0,
            ]);
        }

        return RestHelp::success([
            'id'=>$newDeliverAddressid
        ]);
    }


    /**
     * @param $id
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function destroy($id)
    {
        if( ! $id){
            return RestHelp::parametersIllegal("id is required");
        }

        $buyerAddress = BuyerAddress::find($id);

        if( ! $buyerAddress){
            return RestHelp::encodeResult(23000,'deliver_addr is not found');
        }

        $this->buyerAddressService->destroy($buyerAddress);

        return RestHelp::success();
    }

    /**
     * @param $id
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function update($id)
    {
        if( ! $id){
            return RestHelp::parametersIllegal("id is required");
        }

        $buyerAddress = BuyerAddress::find($id);

        if( ! $buyerAddress){
            return RestHelp::encodeResult(23000,'deliver_addr is not found');
        }


        $buyerId = $this->buyerId;
        $inputData = $this->inputData->all();

        $validator = Validator::make($inputData,[
            'countyId'=>'',
            'address'=>'',
            'postcode'=>'',
            'receiver'=>'',
            'mobile'=>'',
            'default'=>'',

        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $this->buyerAddressService->update(
            $buyerAddress,
            $buyerId,
            isset($inputData['countyId']) ? $inputData['countyId'] : $buyerAddress->county_id,
            isset($inputData['address']) ? $inputData['address'] : $buyerAddress->address,
            isset($inputData['postcode']) ? $inputData['postcode'] : $buyerAddress->postcode,
            isset($inputData['receiver']) ? $inputData['receiver'] : $buyerAddress->receiver,
            isset($inputData['mobile']) ? $inputData['mobile'] : $buyerAddress->mobile,
            isset($inputData['default']) ? $inputData['default'] : $buyerAddress->default
        );


        return RestHelp::success();
    }


    
}