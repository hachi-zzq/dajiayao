@extends('layouts.master')
@section('title')更新厂商@stop
@section('page-title')
    <div class="page-title">
        <h3>更新厂商</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('manufacturers')}}">厂商管理</a></li>
                <li class="active">更新厂商</li>
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
                    <form class="form-horizontal"  action="{{route('updateManufacturer',array('id'=>$manufacturer->id))}}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" name="name"  class="form-control" value="{{$manufacturer->name}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">地址</label>
                            <div class="col-md-4">
                                <input type="text" name="address" class="form-control" value="{{$manufacturer->address}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-md-4">
                                <input type="text" name="email" class="form-control" value="{{$manufacturer->email}}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">网址</label>
                            <div class="col-md-4">
                                <input type="text" name="website" class="form-control" value="{{$manufacturer->website}}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">电话</label>
                            <div class="col-md-4">
                                <input type="text" name="phone" class="form-control" value="{{$manufacturer->phone}}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-md-4">
                                <textarea cols="20" rows="4" class="form-control" name="comment">{{$manufacturer->comment}}</textarea>
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