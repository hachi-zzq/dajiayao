@extends('layouts.master')

@section('title')订单详情@stop

@section('page-title')
<div class="page-title">
    <h3>订单管理</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{route('adminIndex')}}">Home</a></li>
            <li><a href="{{route('adminOrders')}}">订单管理</a></li>
            <li class="active">详情</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@include('layouts.tips')
<h3 class="m-b-sm">
    订单号：{{$order->order_number}}&nbsp;&nbsp;&nbsp;
    {!! $order->getStatusLabel() !!}&nbsp;&nbsp;&nbsp;
    {!! $order->getOrderTypeLabel() !!}
    @if($order->status == \Dajiayao\Model\Order::STATUS_TO_PAY)
        &nbsp;&nbsp;&nbsp;
        <button type="button" class="btn btn-default">
            <a href="{{route('adminOrderCancel', $order->order_number)}}" onclick="return confirm('确认取消该订单吗？')">取消订单</a>
        </button>
    @else
        &nbsp;&nbsp;&nbsp;
        支付流水号：{{$order->payment_serial_number}}
    @endif
</h3>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">购买用户</h3>
            </div>
            <div class="panel-body">
                <p>{{$wxUser->nickname}}
                    @if($wxUser->subscribe)
                        <span class="label label-success">微信用户</span>
                    @endif</p>
                <p>下单于&nbsp;{{$order->created_at}}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">送至</h3>
            </div>
            <div class="panel-body">
                <p>{{$order->receiver_full_address}},&nbsp;{{$order->receiver_postcode}}</p>
                <p>{{$order->receiver}},&nbsp;{{$order->receiver_phone}}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">付款</h3>
            </div>
            <div class="panel-body">
                <p style="font-size: 16px;">¥{{$order->grand_total}} = ¥{{$order->item_total}} + ¥{{$order->postage}} - ¥{{$order->discount_total}}</p>
                <p class="form-control-static">应付 ＝ 总价 + 运费 － 折扣</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">佣金</h3>
            </div>
            <div class="panel-body">
                <p style="font-size: 18px;">¥{{$order->commission->amount}}</p>
                <p class="form-control-static"></p>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">购物明细</h3>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>商品编码</th>
                                <th>商品信息</th>
                                <th>供货商</th>
                                <th>单价</th>
                                <th>数量</th>
                                <th>合计价格</th>
                                <th>佣金</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orderItems as $oi)
                                <tr>
                                    <td>{{$oi->code}}</td>
                                    <td>{{$oi->title}}</td>
                                    <td>{{$oi->supplier->title}}</td>
                                    <td>¥{{$oi->price}}</td>
                                    <td>{{$oi->quantity}}</td>
                                    <td>¥{{$oi->item_total}}</td>
                                    <td>¥{{$oi->commission}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th scope="row">合计</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$order->totalQuantity}}</td>
                                <td>¥{{$order->totalPrice}}</td>
                                <td>¥{{$order->commission->amount}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 发货信息 --}}
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">发货信息</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    @if($order->status == \Dajiayao\Model\Order::STATUS_TO_DELIVER)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">快递公司</label>
                            <div class="col-md-4">
                                <select class="form-control m-b-sm" name="express" id="express_id">
                                    @foreach($expresses as $k => $ex)
                                        <option value="{{$ex->id}}" @if($k == 0){{'selected'}}@endif>{{$ex->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">运单号码</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control m-b-sm" name="express_num" id="express_num" placeholder="输入快递单号">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-default" id="btn-order-deliver">发货</button>
                        </div>
                    @elseif($order->status == \Dajiayao\Model\Order::STATUS_TO_PAY)
                        <p class="form-control-static">等待买家付款</p>
                    @else
                        <div class="form-group">
                            <label class="col-sm-2 control-label">快递公司</label>
                            <p class="form-control-static">{{$order->expressObj->name}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">运单号码</label>
                            <p class="form-control-static">{{$order->express_number}}</p>
                        </div>
                        <div class="form-groupo">
                            <div class="col-sm-offset-2 col-sm-10">
                                <a target="_blank" href="{{sprintf(\Config::get('app.kuaidi100'), $order->express_number)}}">查询物流</a>
                            </div>
                        </div>
                    @endif
                    <input type="hidden" id="hiddenOrderNumber" value="{{$order->order_number}}">
                </form>
            </div>
        </div>
    </div>
    {{-- 订单备注 --}}
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">订单备注</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea class="input-small form-control" name="order-comment" id="order-comment" rows="5" placeholder="输入订单备注"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success" id="btn-order-modify">保存</button>
                            <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop