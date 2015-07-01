@extends('layouts.master')
@section('title')更新公众号@stop

@section('page-title')
    <div class="page-title">
        <h3>更新公众号</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('apps')}}">应用管理</a></li>
                <li class="active">更新公众号</li>
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
                    <form  class="form-horizontal"  action="{{route('updateAppMp',array('id'=>$mp->app_id))}}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" value="{{$mp->name}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">微信ID</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="mp_id" value="{{$mp->wp_id}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">APP_ID</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="appid" value="{{$mp->appid}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">APP_SECRET</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="appsecret" value="{{$mp->appsecret}}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-md-4">
                                <textarea cols="20" rows="4" class="form-control" name="comment">{{$mp->comment}}</textarea>
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