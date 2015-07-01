@extends('layouts.master')
@section('title')设备管理@stop

@section('page-title')
<div class="page-title">
    <h3>设备管理</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/admin">Home</a></li>
            <li class="active">设备管理</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white" style="min-width:900px;">
            <div class="panel-body">
                {{--<div id="flotchart1" style="display: none;"></div>--}}
                {{--<div id="flotchart2" style="display: none;"></div>--}}
                @if(Session::has('result') && Session::get('result') == true)
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    {{Session::get('msg')}}
                </div>
                @endif
                @if(Session::has('result') && Session::get('result') == false)
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    {{Session::get('msg')}}
                </div>
                @endif
                <div class="alert alert-danger alert-dismissible" id="error-tips" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <label style="margin-bottom: 0px;">操作失败</label>
                </div>
                <div class="alert alert-success alert-dismissible" id="success-tips" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    导出成功，请<a href="#" target="_blank" class="alert-link">点此下载</a>
                </div>
                <div role="tabpanel">
                    <div style="padding-bottom: 5px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-sn"><i class="fa fa-plus"></i>增加SN</button>
                        @include('admin.popup.add-sn')
                        <button type="button" class="btn btn-info" id="btn-export-sn">导出</button>
                        @if($status <= 0)
                        <button type="button" class="btn btn-success" id="btn-alloc-top">分配</button>
                        @endif
                        <button type="button" class="btn btn-danger">销毁</button>
                        <button type="button" class="btn btn-default" id="btn-select">筛选</button>
                        {{--@include('admin.popup.allocapp-batch', ['apps' => $apps])--}}
                    </div>
                    <!-- Nav Tab -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" @if($status < 0) class="active" @endif>
                            <a href="/admin/devices" role="tab">设备列表</a>
                        </li>
                        <li role="presentation" @if($status == 0) class="active" @endif>
                            <a href="/admin/devices?status=0" role="tab">待分配</a>
                        </li>
                        <li role="presentation" @if($status == 1) class="active" @endif>
                            <a href="/admin/devices?status=1" role="tab">待烧号</a>
                        </li>
                        <li role="presentation" @if($status == 2) class="active" @endif>
                            <a href="/admin/devices?status=2" role="tab">烧号完成</a>
                        </li>
                    </ul>

                    <!-- Tab Pane -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tab1">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="row"><input type="checkbox" class="check-mail-all" id="check-all">#</th>
                                        <th>SN</th>
                                        <th>UMM</th>
                                        <th>备注</th>
                                        <th>生成日期</th>
                                        <th>配置状态</th>
                                        <th>应用名称</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($devices as $device)
                                        <tr>
                                            <td scope="row">
                                                <label><div class="checker"><input type="checkbox" class="check-item"></div>{{$device->id}}</label>
                                            </td>
                                            <td title="{{$device->sn}}" class="title">{{$device->sn}}</td>
                                            <td>
                                                @if(!empty($device->uuid))
                                                    <a class="toggle-umm-detail" data-toggle="modal" data-target="#umm-detail" style="cursor: pointer;">{{substr($device->uuid, 28, 8)}}</a>
                                                    <input type="hidden" id="hiddenDetailUuid" value="{{$device->uuid}}"/>
                                                    <input type="hidden" id="hiddenDetailMajor" value="{{$device->major}}"/>
                                                    <input type="hidden" id="hiddenDetailMinor" value="{{$device->minor}}"/>
                                                @endif
                                                @include('admin.popup.umm-detail')
                                            </td>
                                            <td>{{$device->comment}}</td>
                                            <td>{{date('Y-m-d', strtotime($device->created_at))}}</td>
                                            @if($device->status == 0)
                                            <td><span class="label label-info">{{$device->status_cn}}</span></td>
                                            @elseif($device->status == 1)
                                            <td><span class="label label-primary">{{$device->status_cn}}</span></td>
                                            @else
                                            <td><span class="label label-success">{{$device->status_cn}}</span></td>
                                            @endif
                                            <td>
                                                @if($device->status > 0){{$device->app->name}}@endif
                                            </td>
                                            <td>
                                                @if($device->status == 0)
                                                    <button type="button" class="btn btn-success btn-xs devices-alloc-app">分配</button>
                                                @elseif($device->status == 1)
                                                    <button type="button" class="btn btn-warning btn-xs devices-burn-in">烧号</button>
                                                @else
                                                @endif
                                                <input type="hidden" class="hiddenDeviceId" id="hiddenDeviceId" value="{{$device->id}}"/>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <?php //echo $devices->appends(['status' => $status])->render(); ?>
                        </div><!-- Tab Pane 1 -->
                    </div>
                </div>
            </div>
        </div><!-- panel -->
    </div><!-- col-md-12 -->
</div><!-- Row -->
<input type="hidden" id="hiddenDeviceStatus" value="{{$status}}"/>
@include('admin.popup.allocapp', ['apps' => $apps])
{{--@include('admin.popup.allocapp-batch', ['apps' => $apps])--}}
@include('admin.popup.burn-in', ['manufacturers' => $manufacturers])
@include('admin.popup.filter', ['apps' => $apps])
@stop

@section('js')
<script src="/themeforest/js/pages/form-elements.js"></script>
@stop