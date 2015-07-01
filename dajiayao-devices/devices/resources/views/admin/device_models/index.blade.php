@extends('layouts.master')
@section('title')型号管理@stop

@section('page-title')
    <div class="page-title">
        <h3>型号管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">型号管理</li>
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
                    <div style="float: right; margin-left: 100px;">
                        <button type="button" class="btn btn-primary" id="btn-add-wxpage"><a style="color: white;" href="{{route('addDeviceModel')}}"><i class="fa fa-plus"></i>增加型号</a></button>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="row">#</th>
                            <th>名称</th>
                            <th>厂商</th>
                            <th>电量有效期(月)</th>
                            <th>默认密码</th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($deviceModels as $deviceModel)
                            <tr>
                                <td scope="row">
                                    <label><div class="checker"></div>{{$deviceModel->id}}</label>
                                </td>
                                <td>{{$deviceModel->name}}</td>
                                <td>{{$deviceModel->manufacturer->name}}</td>
                                <td>{{$deviceModel->battery_lifetime}}</td>
                                <td>{{$deviceModel->default_password}}</td>
                                <td>{{$deviceModel->comment}}</td>
                                <td>
                                    <a href="{{route('updateDeviceModel',array('id'=>$deviceModel->id))}}" title="编辑"><span class="fa fa-edit"></span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- panel -->
        </div>
        <!-- col-md-12 -->
    </div><!-- Row -->
@stop

@section('js')
    <script type="text/javascript">
    </script>
@stop