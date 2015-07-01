@extends('layouts.master')

@section('title')支付方式管理@stop

@section('page-title')
    <div class="page-title">
        <h3>支付方式管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">支付方式管理</li>
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
                                <th scope="row">#</th>
                                <th>排序</th>
                                <th>支付名称</th>
                                <th>支付方式</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paymentTypes as $type)
                                <tr>
                                    <th scope="row">
                                        <label>{{$type->id}}</label>
                                    </th>
                                    <td>{{$type->sort}}</td>
                                    <td>{{$type->name}}</td>
                                    <td>{{$type->type}}</td>
                                    <td>
                                        @if($type->status == \Dajiayao\Model\PaymentType::STATUS_OPEN)
                                            <span class="label label-success">开启</span>
                                        @else
                                            <span class="label label-danger">关闭</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('updatePaymentType',array('id'=>$type->id))}}">编辑</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop