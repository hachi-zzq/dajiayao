@extends('layouts.master')
@section('title')绑定设备@stop

@section('page-title')
    <div class="page-title">
        <h3>摇一摇页面</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="/admin/wxpages">摇一摇页面</a></li>
                <li class="active">绑定设备</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white" style="min-width:900px;">
            <div class="panel-body">
                <div class="alert alert-success" role="alert" style="display: none;"></div>
                <div style="">
                    <button type="button" class="btn btn-primary" id="btn-bind-page-save">保存</button>
                    <button type="button" class="btn btn-success"><a style="color: white;" href="/admin/wxpages">返回</a></button>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <th scope="row"><input type="checkbox" id="check-devices-all"/>设备ID</th>
                        <th>SN</th>
                        <th>备注</th>
                        <th>UUID</th>
                        <th>Major</th>
                        <th>Minor</th>
                        <th>配置页面数</th>
                        {{--<th>操作</th>--}}
                        </thead>
                        <tbody>
                        @foreach($wxdevices as $wxdvc)
                        <tr>
                            <td scope="row"><input type="checkbox" class="check-device-item" @if($wxdvc->bind_status==1)checked @endif/>{{$wxdvc->device_id}}</td>
                            <td>
                                @foreach($wxdvc->device as $d)
                                    {{$d->sn}}<br/>
                                @endforeach
                            </td>
                            <td>{{$wxdvc->comment}}</td>
                            <td>{{$wxdvc->uuid}}</td>
                            <td>{{$wxdvc->major}}</td>
                            <td>{{$wxdvc->minor}}</td>
                            <td>{{$wxdvc->getPageCount()}}</td>
                            <input type="hidden" class="hiddenWxDeviceID" value="{{$wxdvc->id}}"/>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="hiddenWxPageID" value="{{$id}}"/>
@stop