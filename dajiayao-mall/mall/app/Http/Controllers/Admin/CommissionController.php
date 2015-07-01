<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\WithDrawCommission;
use Illuminate\Http\Request;
use Dajiayao\Model\SellerCommission;
use Dajiayao\Services\CommissionService;

class CommissionController extends Controller
{

    protected $inputData;
    public function __construct(Request $request,CommissionService $commissionService)
    {
        $this->inputData = $request->all();
        $this->commissionService = $commissionService;
    }


    public function applyList()
    {
        $input = $this->inputData;
        if( ! array_key_exists('status',$input)){
            $drawCommissionList = WithDrawCommission::paginate(20);
        }else{
            $drawCommissionList = WithDrawCommission::where('status',$input['status'])->paginate(20);
        }

        $cacheArr = array();

        foreach($drawCommissionList as $k=>$rawDrawCommission){
            if( ! array_key_exists($rawDrawCommission->seller_id,$cacheArr)){
                $sellerId = $rawDrawCommission->seller_id;

                //所有佣金
                $sellerCommissions = SellerCommission::where('seller_id',$sellerId)->get();
                $commisionTotal = 0;
                foreach($sellerCommissions as $rowCommission){
                    $commisionTotal += $rowCommission->amount;
                }

                //已经确认的佣金
                $sellerConfirmedCommissions = SellerCommission::where('seller_id',$sellerId)->where('status',SellerCommission::STATUS_CONFIRMED)->get();
                $commisionConfirmTotal = 0;
                foreach($sellerConfirmedCommissions as $rowCommission){
                    $commisionConfirmTotal += $rowCommission->amount;
                }

                //已经提取的佣金
                $drawedCommissions = WithDrawCommission::where('seller_id',$sellerId)->where('status',WithDrawCommission::STATUS_DRAWED)->get();
                $drawedCommissionTotal = 0 ;
                foreach($drawedCommissions as $drawedCommission){
                    $drawedCommissionTotal += $drawedCommission->amount;
                }

                //可以提取的佣金
                $rawDrawCommission->availableCommission = $commisionConfirmTotal - $drawedCommissionTotal;
                $rawDrawCommission->commissionTotal = $commisionTotal;

                $cacheArr[$rawDrawCommission->seller_id] = $rawDrawCommission;
            }else{
                $drawCommissionList[$k] = $cacheArr[$rawDrawCommission->seller_id];
            }

        }

        return view('admin.commission.apply_list')
            ->with('draw_lists',$drawCommissionList)
            ->with('input',$input);
    }



    /**
     * 卖家订单佣金列表 首页
     * @param $sellerId
     * @return $this
     */
    public function sellerCommissionList($sellerId=null)
    {
        $commissionList = $this->commissionService->getSellerCommissionBySeller($sellerId,20);
        return view('admin.commission.seller-commission')->with('commissionList', $commissionList);
    }



}

