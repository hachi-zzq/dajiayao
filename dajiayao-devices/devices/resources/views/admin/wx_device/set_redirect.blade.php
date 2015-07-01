@extends('layouts.master')
@section('title')设置跳转@stop
@section('page-title')
    <div class="page-title">
        <h3>设置跳转</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('adminWxDevicesIndex')}}">设备列表</a></li>

                <li class="active">设置跳转</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">

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
                    <form class="form-horizontal" action="{{route('adminPostSetRedirect')}}" method="post">

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control" id="manufacturer_sn" value="{{Session::get('name') or $device->redirect_name}}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">URL</label>
                            <div class="col-md-4">
                                <input type="text" name="url" class="form-control" id="manufacturer_sn" value="{{Session::get('url') or $device->redirect_url}}"/>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-4">
                                <input type="hidden" name="device_id" value="{{$device_id}}"/>
                                <button type="submit" class="btn btn-success">提交</button>
                                <button type="button" class="btn btn-default" onclick="history.back(-1);" style="margin-left: 20px;">取消</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- panel -->
        </div>
        <!-- col-md-12 -->
    </div><!-- Row -->
@stop
