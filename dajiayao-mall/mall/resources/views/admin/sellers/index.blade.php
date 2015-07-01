@extends('layouts.master')

@section('title')店主管理@stop

@section('page-title')
    <div class="page-title">
        <h3>店主管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">店主管理</li>
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
                    <button type="button" class="btn btn-primary"><a href="{{route('addSeller')}}" style="color: white;">增加店主</a></button>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>头像</th>
                                <th>姓名</th>
                                <th>手机</th>
                                <th>佣金</th>
                                <th>注册时间</th>
                                <th>认证状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sellers as $seller)
                                <tr>
                                    <td width="40">
                                        <a href="{{route('sellerDetail',array('id'=>$seller->id))}}">
                                        @if($seller->wxUser and $seller->wxUser->headimgurl)
                                            <img src="{{$seller->wxUser->headimgurl}}" width="36" title="{{$seller->wxUser->nickname}}">
                                        @else
                                            <img src="/themeforest/images/avatar.png" width="36">
                                        @endif
                                        </a>
                                    </td>
                                    <td>
                                        {{$seller->realname}}
                                    </td>
                                    <td>{{$seller->mobile}}</td>
                                    <td>¥{{$seller->commission}}</td>
                                    <td>{{$seller->created_at->format('Y-m-d H:i:s')}}</td>
                                    <td>
                                        @if($seller->auth_status == \Dajiayao\Model\Seller::AUTH_STATUS_SUCCESS)
                                            <span class="label label-success">{{$seller->getAuthStatusName()}}</span>
                                        @elseif($seller->auth_status == \Dajiayao\Model\Seller::AUTH_STATUS_NONE)
                                            <span class="label label-danger">{{$seller->getAuthStatusName()}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('adminSellerCommission',array('id'=>$seller->id))}}">佣金详细</a>
                                        <a href="{{route('sellerDetail',array('id'=>$seller->id))}}">查看</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!!$sellers->appends(Input::all())->render()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop