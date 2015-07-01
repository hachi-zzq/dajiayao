@extends('layouts.master')

@section('title')店主佣金列表@stop

@section('page-title')
    <div class="page-title">
        <h3>店主佣金列表</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">店主佣金列表</li>
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
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="min-width: 90px;">店主</th>
                                <th style="min-width: 90px">订单编号</th>
                                <th style="min-width: 90px">订单总额</th>
                                <th style="min-width: 90px">佣金</th>
                                <th style="min-width: 100px">产生时间</th>
                                <th style="min-width: 90px">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($commissionList as $commission)
                                <tr>
                                    <td>
                                        <a href="{{route('sellerDetail',array('id'=>$commission->seller->id))}}">{{$commission->seller->realname}}</a>
                                    </td>
                                    <td>
                                        <a href="{{route('adminOrderDetail', $commission->order->order_number)}}">{{$commission->order->order_number}}</a>
                                    </td>
                                    <td>
                                        ¥{{$commission->order->amount_tendered}}
                                    </td>
                                    <td>
                                        ¥{{$commission->amount}}
                                    </td>
                                    <td>
                                        {{$commission->created_at}}
                                    </td>
                                    <td>
                                        @if($commission->status == \Dajiayao\Model\SellerCommission::STATUS_CONFIRMED)
                                            <span class="label label-success">确认</span>
                                        @else
                                            <span class="label label-danger">未确认</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!!$commissionList->appends(Input::all())->render()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop