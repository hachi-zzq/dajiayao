<?php namespace Dajiayao\Http\Controllers\Seller;

use Dajiayao\Model\Seller;
use Dajiayao\Model\SellerCommission;
use Dajiayao\Model\WithDrawCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionController extends BaseController
{

    protected $seller;
    protected $inputData;

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->seller = Seller::find($this->sellerId);
        $this->inputData = $request;
    }


    /**
     * 个人佣金详细
     * @author zhengqian@dajiayao.cc
     */
    public function commissionDetail()
    {
        //所有佣金
        $sellerCommissions = SellerCommission::where('seller_id',$this->sellerId)->get();
        $commisionTotal = 0;
        foreach($sellerCommissions as $rowCommission){
            $commisionTotal += $rowCommission->amount;
        }

        //已经提取的佣金
        $drawedCommissions = WithDrawCommission::where('seller_id',$this->sellerId)->where('status',WithDrawCommission::STATUS_DRAWED)->get();
        $drawedCommissionTotal = 0 ;
        foreach($drawedCommissions as $drawedCommission){
            $drawedCommissionTotal += $drawedCommission->amount;
        }

        //可以提取的佣金
        $avaliableCommission = $commisionTotal - $drawedCommissionTotal;
    }


    /**
     * 申请提现佣金
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author zhengqian@dajiayao.cc
     */
    public function getApplyDraw()
    {

        if( ! $this->seller){
            echo "操作不合法";
            exit;
        }

        if( ! $this->seller->account_number){
            return redirect(route('bindBankCard'));
        }

        return view('seller.commission.apply')->with('seller',$this->seller);
    }

    /**
     * 提现佣金
     * @author zhengqian@dajiayao.cc
     */
    public function postApplyDraw()
    {
        $inputData = $this->inputData->all();
        $validator = Validator::make($inputData,[

        ]);

        $applyCommissionNum = date("YmdHis").rand(0,9);
        $drawCommission = new WithDrawCommission();
        $drawCommission->withdraw_number = $applyCommissionNum;
        $drawCommission->seller_id = $this->sellerId;
        $drawCommission->amount = $inputData['amount'];
        $drawCommission->account_name = $this->seller->realname;
        $drawCommission->account_number = $this->seller->account_number;
        $drawCommission->opening_bank = $this->seller->opening_bank;
        $drawCommission->status = WithDrawCommission::STATUS_DRAWEDING;
        $drawCommission->save();

    }

    /**
     * 绑定银行卡
     * @author zhengqian@dajiayao.cc
     */
    public function getBindBankCard()
    {
        if( ! $this->seller){
            echo "操作不合法";
            exit;
        }

        return view('seller.commission.bind_bankcard')->with('seller',$this->seller);
    }

    /**
     * 绑定银行卡
     * @author zhengqian@dajiayao.cc
     */
    public function postBindBankCard()
    {
        $seller = $this->seller;
        $inputData = $this->inputData->all();
        $validator = Validator::make($inputData,[
            'realname'=>'required',
            'account_number'=>'required',
            'opening_bank'=>'required'
        ]);

        if($validator->fails()){
            echo $validator->messages()->first();
            exit;
        }

        $seller->realname = $inputData['realname'];
        $seller->account_number = $inputData['account_number'];
        $seller->opening_bank = $inputData['opening_bank'];
        $seller->save();
    }

    /**
     * 修改绑定的银行卡
     * @author zhengqian@dajiayao.cc
     */
    public function getModifyBankCard()
    {
        return view('seller.commission.modify_bankcard')->with('seller',$this->seller);
    }

    /**
     * 修改绑定的银行卡
     * @author zhengqian@dajiayao.cc
     */
    public function postModifyBankCard()
    {
        $seller = $this->seller;
        $inputData = $this->inputData->all();
        $validator = Validator::make($inputData,[

        ]);

        if($validator->fails()){
            echo $validator->messages()->first();
            exit;
        }
        $seller->account_number = $inputData['account_number'];
        $seller->opening_bank = $inputData['opening_bank'];
        $seller->save();
    }




}