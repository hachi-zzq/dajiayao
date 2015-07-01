<?php namespace Dajiayao\Http\Controllers\Rest\V1;
use Dajiayao\Library\Help\RestHelp;
use Dajiayao\Library\Help\Tool;
use Dajiayao\Model\Address;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;


/**
 * Class BaseController
 * @package Dajiayao\Http\Controllers
 */

class RegionController extends BaseController
{

    /**获取当前位置
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function location()
    {
        $inputData = $this->inputData->only('longitude','latitude');

        $validator = Validator::make($inputData,[
            'longitude'=>'required',
            'latitude'=>'required'
        ]);

        if($validator->fails()){
            return RestHelp::parametersIllegal($validator->messages()->first());
        }

        $baiduMapApi = Config::get('baidumap.api.inverse_address');
        $ret = Tool::getCurl(sprintf($baiduMapApi,$inputData['longitude'],$inputData['latitude']));

        if( $ret->httpCode != 200 or $ret->errno != 0){
            return RestHelp::encodeResult(23002,"location fails");
        }

        $baiduRet = json_decode($ret->content);

        $formattedAddress = $baiduRet->result->formatted_address;
        $spec = $baiduRet->result->sematic_description;

        $province = $baiduRet->result->addressComponent->province;

        $trimProvince = ['省'];

        $province = $this->strTrim($province,$trimProvince);


        $province = Address::where("address",'like',"%$province%")->first();


        if( ! $province){
            return RestHelp::encodeResult(23003,"can not location province");
        }

        $city = $baiduRet->result->addressComponent->city;
        $trimCity = ['市'];

        $city = $this->strTrim($city,$trimCity);

        $city = Address::where("address",'like',"%$city%")->where('parent_id',$province->id)->first();
        if( ! $city){
            return RestHelp::encodeResult(23003,"can not location city");
        }


        $district = $baiduRet->result->addressComponent->district;

        $trimDistrict = ['区','县','镇'];

        $district = $this->strTrim($district,$trimDistrict);


        $district = Address::where("address",'like',"%$district%")->first();


        if( ! $district){
            return RestHelp::encodeResult(23003,"can not location district");
        }

        $arrRet = array();
        $arrRet['province']['id'] = $province->id;
        $arrRet['province']['name'] = $province->address;
        $arrRet['city']['id'] = $city->id;
        $arrRet['city']['name'] = $city->address;
        $arrRet['county']['id'] = $district->id;
        $arrRet['county']['name'] = $district->address;
        $arrRet['formatted'] = $formattedAddress;
        $arrRet['specific'] = $spec;

        return RestHelp::success($arrRet);
    }


    public function strTrim($str,array $trimArr)
    {
        $last =  mb_substr($str,mb_strlen($str)-1,1);

        if(in_array($last,$trimArr)){
            return mb_substr($str,0,mb_strlen($str)-1);
        }

        return $str;

    }



    /**
     * 省列表
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function provinceIndex()
    {

        $province = Address::where('parent_id',0)->get();
        $arrRet = array();
        foreach($province as $k=>$p){
            $arrRet[$k]['id'] = $p->id;
            $arrRet[$k]['name'] = $p->address;
        }

        return RestHelp::success($arrRet);
    }

    /**市的列表
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function cityIndex()
    {

        $provinceId = $this->inputData->get('provinceId');

        if( ! $provinceId){
            return RestHelp::parametersIllegal("province_id is required");
        }

        $city = Address::where('parent_id',$provinceId)->get();

        $arrRet = array();
        foreach($city as $k=>$p){
            $arrRet[$k]['id'] = $p->id;
            $arrRet[$k]['name'] = $p->address;
        }
        return RestHelp::success($arrRet);
    }

    /**区/县列表
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function countyIndex()
    {

        $cityId = $this->inputData->get('cityId');

        if( ! $cityId){
            return RestHelp::parametersIllegal("city_id is required");
        }

        $county = Address::where('parent_id',$cityId)->get();

        $arrRet = array();
        foreach($county as $k=>$p){
            $arrRet[$k]['id'] = $p->id;
            $arrRet[$k]['name'] = $p->address;
        }

        return RestHelp::success($arrRet);
    }

}