@extends('layouts.master')

@section('title')店主详情@stop

@section('page-title')
    <div class="page-title">
        <h3>增加店主</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('adminIndex')}}">Home</a></li>
                <li><a href="{{route('sellers')}}">店主管理</a></li>
                <li class="active">店主详情</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    @include('layouts.tips')
    <h3 class="m-b-sm">
        店主：{{$seller->realname}}
        &nbsp;&nbsp;&nbsp;
        @if($seller->auth_status == \Dajiayao\Model\Seller::AUTH_STATUS_SUCCESS)
            <span class="label label-success">{!!$seller->getAuthStatusName()!!}</span>
        @elseif($seller->auth_status == \Dajiayao\Model\Seller::AUTH_STATUS_NONE)
            <span class="label label-danger">{!!$seller->getAuthStatusName()!!}</span>
        @endif
    </h3>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3 class="panel-title">基本信息</h3>
                </div>
                <div class="panel-body">
                    <p class="form-control-static">手机号：{{$seller->mobile}}</p>

                    <p class="form-control-static">佣金：¥{{$seller->commission}}</p>

                    <p class="form-control-static">银行卡号：{{$seller->account_number}}</p>

                    <p class="form-control-static">开户行：{{$seller->opening_bank}}</p>

                    <p class="form-control-static">注册时间：{{$seller->created_at}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3 class="panel-title">微信信息</h3>
                </div>
                <div class="panel-body">
                    @if($seller->wxUser)
                        <p class="form-control-static">头像：
                            <img src="{{$seller->wxUser->headimgurl}}" width="36" title="{{$seller->wxUser->nickname}}">
                        </p>
                        <p class="form-control-static">昵称：{{$seller->wxUser->nickname}}</p>
                        <p class="form-control-static">省市：{{$seller->wxUser->province}} {{$seller->wxUser->city}} </p>
                        <p class="form-control-static">关注时间：{{$seller->wxUser->created_at}}</p>
                    @else
                        <p class="form-control-static">头像：
                            <img src="/themeforest/images/avatar.png" width="36">
                        </p>
                        <p class="form-control-static">昵称：</p>
                        <p class="form-control-static">省市： </p>
                        <p class="form-control-static">关注时间：</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-offset-5 col-sm-10">
                <button type="button" class="btn btn-default" onclick="window.history.back()">返回</button>
            </div>
        </div>
    </div>

@stop