@extends('layouts.master')
@section('title')增加型号@stop

@section('page-title')
    <div class="page-title">
        <h3>增加型号</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('deviceModels')}}">型号管理</a></li>
                <li class="active">增加型号</li>
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

                    <form class="form-horizontal"  action="{{route('addDeviceModel')}}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">厂商</label>
                            <div class="col-md-4">
                                <select class="form-control m-b-sm" name="manufacturer_id">
                                    @foreach($manufacturerList as $manufacturer)
                                        <option value="{{$manufacturer->id}}">{{{$manufacturer->name}}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">电量有效期(月)</label>
                            <div class="col-md-4">
                                <input type="text" name="battery_lifetime"  class="form-control" value="" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">默认密码</label>
                            <div class="col-md-4">
                                <input type="text" name="default_password" class="form-control" value="" />
                                <p class="help-block">配置设备所需要的连接密码</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-md-4">
                                <textarea cols="20" rows="4" class="form-control" name="comment"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" name="_token" value="{!! csrf_token()!!}"/>
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

@section('js')
    <script type="text/javascript">
    </script>
@stop