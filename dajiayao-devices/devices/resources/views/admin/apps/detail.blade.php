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
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{$app->name}}"/>
                            </div>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户</label>

                                <div class="col-sm-10">
                                    <select class="form-control m-b-sm" disabled="disabled" name="user_id"
                                            value="{{$app->user_id}}">
                                        @foreach($user as $userList)
                                            <option value="{{$user->id}}">{{{$user->name}}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户</label>

                                <div class="col-sm-10">
                                    {{{\Illuminate\Support\Facades\Auth::user()->username}}}
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-10">
                                <select class="form-control m-b-sm" disabled="disabled" name="type" value="{{$app->type}}">
                                    <option value="1">微信</option>
                                    <option value="0">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">APP_ID</label>
                            <div class="col-sm-10">
                                <input type="text" name="app_id" value="{{$app->app_id}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">APP_SECRET</label>
                            <div class="col-sm-10">
                                <input type="text" name="app_secret" value="{{$app->app_secret}}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">设备URL</label>
                            <div class="col-sm-10">
                                <input type="text" name="app_secret" value="{{$app->device_url}}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-sm-10">
                                <input type="text" name="comment" value="{{$app->comment}}"/>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-10">
                                <input type="hidden" name="_token" value="{!! csrf_token()!!}"/>
                                <button type="submit" class="btn btn-success">提交</button>
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