@extends('layouts.master')

@section('title')店铺管理@stop

@section('page-title')
    <div class="page-title">
        <h3>店铺管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">店铺管理</li>
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
                                <th style="min-width: 120px;max-width: 300px;">店铺名称</th>
                                <th style="min-width: 90px">店主</th>
                                <th style="min-width: 90px">店铺类型</th>
                                <th style="min-width: 90px">店铺ID</th>
                                <th style="min-width: 90px">营业状态</th>
                                <th style="min-width: 90px">激活状态</th>
                                <th style="min-width: 100px">注册时间</th>
                                <th style="min-width: 120px">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shops as $shop)
                                <tr @if($shop->is_direct_sale=="Y")class="success"@endif>
                                    <td>
                                        <a href="{{route('shopItems',array('id'=>$shop->id))}}">
                                        <span class="pull-left thumb-sm m-r-xs">
                                            @if($shop->thumbnail)
                                                <img src="{{$shop->thumbnail}}" style="height:36px;padding-right: 4px;">
                                            @else
                                                <img src="/themeforest/images/0.png" width="36">
                                            @endif
                                        </span>
                                        {{$shop->name}}</a>
                                    </td>
                                    <td>
                                        @if($shop->seller)
                                            {{$shop->seller->realname}}
                                        @endif
                                    </td>
                                    <td>{{$shop->getTypeName()}}</td>
                                    <td>{{$shop->short_id}}</td>
                                    <td>{{$shop->getOpenStatusName()}}</td>
                                    <td>
                                        @if($shop->status == \Dajiayao\Model\Shop::STATUS_ACTIVE)
                                            <span class="label label-success">{{$shop->getStatusName()}}</span>
                                        @elseif($shop->status == \Dajiayao\Model\Shop::STATUS_INACTIVE)
                                            <span class="label label-danger">{{$shop->getStatusName()}}</span>
                                        @endif
                                    <td>{{$shop->created_at->format('Y-m-d')}}</td>
                                    <td>
                                        <a href="{{route('updateShop',array('id'=>$shop->id))}}">编辑</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!!$shops->appends(Input::all())->render()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop