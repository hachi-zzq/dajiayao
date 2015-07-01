@extends('layouts.master')

@section('title')订单管理@stop

@section('page-title')
<div class="page-title">
    <h3>订单管理</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{route('adminIndex')}}">Home</a></li>
            <li class="active">订单管理</li>
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
                        <li role="presentation" @if($status < 0)class="active"@endif>
                            <a href="{{route('adminOrders')}}" role="tab">全部</a>
                        </li>
                        <li role="presentation" @if($status == \Dajiayao\Model\Order::STATUS_TO_PAY)class="active"@endif>
                            <a href="{{action('Admin\OrderController@index', ['status' => \Dajiayao\Model\Order::STATUS_TO_PAY])}}" role="tab">待支付</a>
                        </li>
                        <li role="presentation" @if($status == \Dajiayao\Model\Order::STATUS_TO_DELIVER)class="active"@endif>
                            <a href="{{action('Admin\OrderController@index', ['status' => \Dajiayao\Model\Order::STATUS_TO_DELIVER])}}" role="tab">待发货</a>
                        </li>
                        <li role="presentation" @if($status == \Dajiayao\Model\Order::STATUS_TO_RECEIVE)class="active"@endif>
                            <a href="{{action('Admin\OrderController@index', ['status' => \Dajiayao\Model\Order::STATUS_TO_RECEIVE])}}" role="tab">待收货</a>
                        </li>
                        <li role="presentation" @if($status == \Dajiayao\Model\Order::STATUS_FINISH)class="active"@endif>
                            <a href="{{action('Admin\OrderController@index', ['status' => \Dajiayao\Model\Order::STATUS_FINISH])}}" role="tab">已完成</a>
                        </li>
                        <li role="presentation" @if($status == \Dajiayao\Model\Order::STATUS_CLOSED)class="active"@endif>
                            <a href="{{action('Admin\OrderController@index', ['status' => \Dajiayao\Model\Order::STATUS_CLOSED])}}" role="tab">已关闭</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="row"><input type="checkbox" id="check-orders-all"></th>
                                        <th>订单号</th>
                                        @if($status < 0)
                                        <th>状态</th>
                                        @endif
                                        <th>下单时间</th>
                                        <th>买家</th>
                                        <th>小店</th>
                                        <th>订单金额</th>
                                        <th>支付方式</th>
                                        <th>收货地区</th>
                                        <th>备注</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td scope="row">
                                                <label>
                                                    <div class="checker">
                                                        <input type="checkbox" class="check-orders-single">
                                                    </div>
                                                </label>
                                            </td>
                                            <td><a href="{{route('adminOrderDetail', $order->order_number)}}">{{$order->order_number}}</a></td>
                                            @if($status < 0)
                                            <td>{!! $order->getStatusLabel() !!}</td>
                                            @endif
                                            <td>{{$order->created_at->format('Y-m-d H:i:s')}}</td>
                                            <td>{{$order->buyerWx->nickname}}</td>
                                            <td>{{$order->shopObj->name}}</td>
                                            <td>{{$order->item_total}}</td>
                                            <td>{{$order->getPaymentTypeText()}}</td>
                                            <td>{{$order->address->address}}</td>
                                            <td>{{$order->comment}}</td>
                                            <td>{!! $order->getAvailableOp() !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!!$orders->appends(Input::all())->render()!!}
                            </div>
                        </div>
                    </div><!-- tab-content -->

                </div>
            </div>
        </div>
    </div>
</div>
@stop