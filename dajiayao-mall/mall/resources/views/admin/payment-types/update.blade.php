@extends('layouts.master')

@section('title')支付方式管理@stop

@section('page-title')
<div class="page-title">
    <h3>支付方式管理</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/admin">Home</a></li>
            <li><a href="{{route('paymentTypes')}}">支付方式管理</a></li>
            <li class="active">编辑</li>
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
                <form class="form-horizontal" action="{{route('updatePaymentType',array('id'=>$paymentType->id))}}" method="post">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">支付名称</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="" readonly="readonly" value="{{$paymentType->name}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">支付类型</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="" readonly="readonly" value="{{$paymentType->type}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">排序</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="sort" d="" value="{{$paymentType->sort}}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">状态</label>
                        <div class="col-md-4">
                            <label><input type="radio" name="status" id="" value="{{\Dajiayao\Model\PaymentType::STATUS_OPEN}}" @if($paymentType->status == \Dajiayao\Model\PaymentType::STATUS_OPEN){{'checked'}}@endif>开启</label>
                            <label><input type="radio" name="status" id="" value="{{\Dajiayao\Model\PaymentType::STATUS_CLOSE}}" @if($paymentType->status == \Dajiayao\Model\PaymentType::STATUS_CLOSE){{'checked'}}@endif>关闭</label>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">确定修改</button>
                        <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                    </div>
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                </form>
            </div>
        </div>
    </div>
</div>
@stop