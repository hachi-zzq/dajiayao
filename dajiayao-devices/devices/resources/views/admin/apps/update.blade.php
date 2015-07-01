@extends('layouts.master')
@section('title')更新应用@stop

@section('page-title')
    <div class="page-title">
        <h3>更新应用</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('apps')}}">应用管理</a></li>
                <li class="active">更新应用</li>
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
                    <form  class="form-horizontal"  action="{{route('updateApp',array('id'=>$app->id))}}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" value="{{$app->name}}"/>
                            </div>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户</label>

                                <div class="col-md-4">
                                    <select class="form-control m-b-sm" name="user_id"
                                            value="{{$app->user_id}}">
                                        @foreach($userList as $user)
                                            <option value="{{$user->id}}">{{{$user->username}}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户</label>

                                <div class="col-md-4">
                                    {{{\Illuminate\Support\Facades\Auth::user()->username}}}
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类型</label>
                            <div class="col-md-4">
                                <select class="form-control m-b-sm" name="type" value="{{$app->type}}">
                                    <option value="1">微信</option>
                                    <option value="0">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">APP_ID</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="app_id" value="{{$app->app_id}}" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">APP_SECRET</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="app_secret" name="app_secret" value="{{$app->app_secret}}" readonly="readonly" />
                                <p class="help-block">访问平台API使用的密钥,<a href="javascript:void(0);" id="get_app_secret">点此</a>生成新密钥。</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">设备URL</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="device_url" value="{{$app->device_url}}" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-md-4">
                                <textarea cols="20" rows="4" class="form-control" name="comment">{{$app->comment}}</textarea>
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
        $(function(){
            $('#get_app_secret').click(function(){
                $.get("/admin/apps/ajax/app-secret", {}, function(data){
                    $('#app_secret').val(data);
                });
            });
        });
    </script>
@stop