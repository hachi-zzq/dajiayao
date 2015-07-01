@extends('layouts.master')

@section('title')佣金@stop

@section('page-title')
<div class="page-title">
    <h3>佣金</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{route('adminIndex')}}">Home</a></li>
            <li class="active">佣金申请处理</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-body">
                @include('layouts.tips')
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" @if( ! array_key_exists('status',$input))class="active" @endif>
                            <a href="{{route('applyList')}}" role="tab">全部</a>
                        </li>
                        <li role="presentation" @if(array_key_exists('status',$input) && $input['status'] == \Dajiayao\Model\WithDrawCommission::STATUS_DRAWEDING) class="active" @endif>
                            <a href="{{route('applyList', ['status' => \Dajiayao\Model\WithDrawCommission::STATUS_DRAWEDING])}}" role="tab">待处理</a>
                        </li>
                        <li role="presentation" @if(array_key_exists('status',$input) && $input['status'] == \Dajiayao\Model\WithDrawCommission::STATUS_DRAWED) class="active" @endif>
                            <a href="{{route('applyList', ['status' => \Dajiayao\Model\WithDrawCommission::STATUS_DRAWED])}}" role="tab">申请成功</a>
                        </li>
                        <li role="presentation" @if(array_key_exists('status',$input) && $input['status'] == \Dajiayao\Model\WithDrawCommission::STATUS_FAIL) class="active" @endif>
                            <a href="{{route('applyList', ['status' => \Dajiayao\Model\WithDrawCommission::STATUS_FAIL])}}" role="tab">申请失败</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="row"><input type="checkbox" id="check-orders-all"></th>
                                        <th>申请编号</th>
                                        <th>申请人</th>
                                        <th>申请金额</th>
                                        <th>佣金总额</th>
                                        <th>可提现金额</th>
                                        <th>状态</th>
                                        <th>操作</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($draw_lists as $list)
                                        <tr>
                                            <td scope="row">
                                                <label>
                                                    <div class="checker">
                                                        <input type="checkbox" class="check-orders-single">
                                                    </div>
                                                </label>
                                            </td>
                                            <td>{{$list->withdraw_number}}</td>
                                            <td>{{$list->seller->wxUser->nickname}}</td>
                                            <td>{{$list->amount}}</td>
                                            <td>{{$list->commissionTotal}}</td>
                                            <td>{{$list->availableCommission}}</td>
                                            <td>{!! $list->getStatusLabel() !!}</td>
                                            <td>
                                                <a href="">佣金明细</a>
                                                <a href="">支付</a>
                                                <a href="">禁止</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!!$draw_lists->appends(Input::all())->render()!!}
                            </div>
                        </div>
                    </div><!-- tab-content -->

                </div>
            </div>
        </div>
    </div>
</div>
@stop