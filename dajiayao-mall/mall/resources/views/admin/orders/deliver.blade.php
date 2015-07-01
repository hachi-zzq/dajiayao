@extends('layouts.master')

@section('title')发货@stop

@section('page-title')
<div class="page-title">
    <h3>发货</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{route('adminIndex')}}">Home</a></li>
            <li><a href="{{route('adminOrders')}}">订单管理</a></li>
            <li class="active">发货</li>
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
                <form class="form-horizontal" action="{{route('adminOrderDeliverPost')}}" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">订单号</label>
                        <div class="col-md-4">
                            <p class="form-control-static">{{$order->order_number}}</p>
                        </div>
                        <input type="hidden" name="order_number" value="{{$order->order_number}}">
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">发货地址</label>
                        <div class="col-md-4">
                            <p class="form-control-static">{{$order->sender_address}}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">收货地址</label>
                        <div class="col-md-4">
                            <p class="form-control-static">{{$order->receiver_full_address}}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">快递公司</label>
                        <div class="col-md-4">
                            <select name="express_id" class="form-control m-b-sm">
                                @foreach($expresses as $k => $exp)
                                    <option value="{{$exp->id}}" @if($k == 0){{'selected'}}@endif>{{$exp->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">快递单号</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="express_num" maxlength="20">
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">确定发货</button>
                        <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                    </div>
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                </form>
            </div>
        </div>
    </div>
</div>
@stop